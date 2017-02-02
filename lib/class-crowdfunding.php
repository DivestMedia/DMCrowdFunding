<?php if(!defined('DM_CROWD_VERSION')) die('Fatal Error');
use Intervention\Image\ImageManagerStatic as Image;
if(!class_exists('DMCROWD')){



    class DMCROWD
    {
        public function __CONSTRUCT(){
            add_action('init', [&$this, 'init']);
        }

        public function init(){
            $this->registerShortcode();
            add_action( 'wp_ajax_save_profile_photo', [$this,'save_profile_photo'] );
            add_action( 'wp_ajax_uploadCampaignProfilePhoto', [$this,'uploadCampaignProfilePhoto'] );
            add_action( 'wp_ajax_nopriv_uploadCampaignProfilePhoto', [$this,'uploadCampaignProfilePhoto'] );
        }

        public function uploadCampaignProfilePhoto(){
            $info = pathinfo($_FILES['image']['name']);
            $ext = $info['extension']; // get the extension of the file
            $filename = 'profile' . date('Ymd') . hash('crc32',$info['basename']) . '.' . $ext;
            $url = '';

            $uploadDir = DM_CROWD_PLUGIN_DIR . 'uploads/';
            $status = 'FAILED';
            if(is_writable($uploadDir)){
                $img = Image::make($_FILES['image']['tmp_name'])
                ->fit(640, 320, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->save($uploadDir . $filename);
                $url = DM_CROWD_PLUGIN_URL . 'uploads/' . $filename;
                $status = 'OK';
            }else{
                $status = 'NOT_WRITEABLE';
            }

            exit(json_encode([
                'status' => $status,
                'url' => $url
            ]));
        }

        public function registerShortcode(){
            add_shortcode( 'createcrowdfunding', function($atts){

                $scriptsPack = [
                    'https://vuejs.org/js/vue.js',
                    '//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js',
                    '//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js',
                    'https://unpkg.com/axios@0.15.3/dist/axios.min.js',
                    DM_CROWD_PLUGIN_URL . 'assets/dropzone.js',
                    DM_CROWD_PLUGIN_URL . 'assets/crowdfunding.js',
                ];

                foreach ($scriptsPack as $key => $url) {
                    wp_register_script('crowdfunding-script' . ($key+1), $url , [] );
                    wp_enqueue_script('crowdfunding-script' . ($key+1));
                }

                $stylePack = [
                    'http://cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css',
                    DM_CROWD_PLUGIN_URL . 'assets/crowdfunding.css',
                ];

                foreach ($stylePack as $key => $url) {
                    wp_register_style('crowdfunding-style' . ($key+1), $url , [] );
                    wp_enqueue_style('crowdfunding-style' . ($key+1));
                }

                include DM_CROWD_PLUGIN_DIR . 'partials/create-campaign.php';
            });
        }

        public static function activate(){

            $pageCampaign = get_posts([
                'name' => 'create-campaign'
            ]);

            if(count($pageCampaign)) $pageCampaign = array_shift($pageCampaign);
            else{
                wp_insert_post([
                    'post_title' => 'Create Campaign',
                    'post_name' => 'create-campaign',
                    'post_type' => 'page',
                    'post_status' => 'publish',
                    'post_content' => '[createcrowdfunding]'
                ]);
            }

        }

        public static function deactivate(){

        }

    }
}
