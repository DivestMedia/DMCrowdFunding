<?php if(!defined('DM_CROWD_VERSION')) die('Fatal Error');

if(!class_exists('DMCROWD')){
    class DMCROWD
    {
        public function __CONSTRUCT(){


            add_action('init', [&$this, 'init']);
        }

        public function init(){
            $this->registerShortcode();
        }

        public function registerShortcode(){
            add_shortcode( 'createcrowdfunding', function($atts){

                $scriptsPack = [
                    'https://vuejs.org/js/vue.js',
                    '//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js',
                    '//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js',
                    'https://unpkg.com/axios@0.15.3/dist/axios.min.js',
                    DM_CROWD_PLUGIN_URL . 'assets/crowdfunding.js',
                ];

                foreach ($scriptsPack as $key => $url) {
                    wp_register_script('crowdfunding-script' . ($key+1), $url , [] );
                    wp_enqueue_script('crowdfunding-script' . ($key+1));
                }

                $stylePack = [
                    'http://cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css',
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
