<div id="create-campaign">
    <ul class="process-steps nav nav-tabs nav-justified">
        <li class="active">
            <a href="#step1" data-toggle="tab">1</a>
            <h5>Create Campaign</h5>
        </li>
        <li>
            <a href="#step2" data-toggle="tab">2</a>
            <h5>Your Story</h5>
        </li>
        <li>
            <a href="#step3" data-toggle="tab">3</a>
            <h5>Rewards</h5>
        </li>
        <li>
            <a href="#step4" data-toggle="tab">4</a>
            <h5>Recipient</h5>
        </li>
    </ul>
    <div class="row">
        <div class="col-sm-4">
            <h4>Preview</h4>
            <figure class="">
                <img class="img-responsive" :src="campaign.profilePhoto"/>
                <div class="progress progress-xs">
                    <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%; min-width: 2em;">
                        <span class="sr-only">60% Complete</span>
                    </div>
                </div>
            </figure>
            <div class="row">
                <div class="col-sm-5">
                    <strong v-if="currencies[campaign.currency]">{{ campaign.goal | currency( currencies[campaign.currency].symbol , 2) }}</strong><br>
                    <span>funding goal</span>
                </div>
                <div class="col-sm-3">
                    <template v-if="campaign.type=='timed'">
                        <strong>{{ daysToGo }}</strong><br>
                        <span>days to go</span>
                    </template>
                    <template v-else>
                        <strong>ONGOING</strong><br>
                        <span>CAMPAIGN</span>
                    </template>
                </div>
            </div>
            <hr />
            <p class="bold uppercase">
                <template v-if="campaign.title">
                    {{ campaign.title }}
                </template>
                <template v-else>
                    <span class="text-gray">Your Campaign Title</span>
                </template>

                <br>
                <small>
                    <span class="icon">
                        <i class="fa fa-tag"></i>
                    </span>
                    <template v-if="campaign.category">
                        {{ campaign.category }}
                    </template>
                    <template v-else>
                        <span class="text-gray">General</span>
                    </template>
                </small>

                <br>
                <small>
                    <span class="icon">
                        <i class="fa fa-map-marker"></i>
                    </span>
                    <template v-if="campaign.location">
                        {{ campaign.location }}
                    </template>
                    <template v-else>
                        <span class="text-gray">Location</span>
                    </template>
                </small>
            </p>
            <p>
                {{ campaign.overview }}
            </p>
        </div>


        <div class="col-sm-8">
            <div class="tab-content margin-top-60">
                <div role="tabpanel" class="tab-pane active" id="step1">

                    <h4>Campaign Title</h4>
                    <div class="fancy-form">
                        <i class="fa fa-user"></i>
                        <input type="text" class="form-control" placeholder="Campaign Title" v-model="campaign.title">
                        <span class="fancy-tooltip top-right">
                            <em>What should we call your campaign?</em>
                        </span>
                    </div>
                    <br>

                    <h4>Category</h4>
                    <div class="fancy-form fancy-form-select">
                        <select class="form-control" v-model="campaign.category">
                            <option disabled>Select a category</option>
                            <option>Arts &amp; Craft</option>
                            <option>Animals &amp; Pets</option>
                            <option>Automobile &amp; Transportation</option>
                            <option>Business &amp; Startups</option>
                            <option>Charity &amp; Non-profit</option>
                            <option>Community</option>
                            <option>Education &amp; Learning</option>
                            <option>Family &amp; Childcare</option>
                            <option>Journalism</option>
                            <option>Legal</option>
                            <option>Medical &amp; Health</option>
                            <option>Music &amp; Entertainment</option>
                            <option>Personal &amp; Pleasure</option>
                            <option>Special Events</option>
                            <option>Sports &amp; Fitness</option>
                            <option>Technology</option>
                        </select>
                        <i class="fancy-arrow"></i>
                    </div>
                    <br>

                    <h4>Type of Campaign</h4>
                    <div class="row">

                        <!-- tabs -->
                        <div class="col-md-3 col-sm-3">
                            <ul class="nav nav-tabs nav-stacked nav-alternate">
                                <li :class="{ 'active' : campaign.type=='timed'}" @click="campaign.type='timed'">
                                    <a href="#campaign-type-1" data-toggle="tab" >
                                        Timed Project
                                    </a>
                                </li>
                                <li :class="{ 'active' : campaign.type=='ongoing'}" @click="campaign.type='ongoing'">
                                    <a href="#campaign-type-2" data-toggle="tab">
                                        Ongoing Campaign
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- tabs content -->
                        <div class="col-md-9 col-sm-9">
                            <div class="tab-content tab-stacked nav-alternate">
                                <div id="campaign-type-1" class="tab-pane" :class="{ 'active' : campaign.type=='timed'}">
                                    <h4>Timed Project</h4>
                                    <div class="row">
                                        <div class='col-md-5'>
                                            <div class="form-group">
                                                <div class='input-group date' id='timed-start-date'>
                                                    <input type='text' class="form-control" v-model.lazy="campaign.startDate"/>
                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class='col-md-5'>
                                            <div class="form-group">
                                                <div class='input-group date' id='timed-end-date'>
                                                    <input type='text' class="form-control" v-model.lazy="campaign.endDate"/>
                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <small>Projects with shorter durations have higher success rates. You won’t be able to adjust your duration after you launch.</small>
                                    <br>
                                    <br>
                                    <h4>Funding Goal</h4>
                                    <p>
                                        <input type="number" class="form-control" placeholder="$ 100,000.00" v-model="campaign.goal" step="500"/>
                                    </p>
                                </div>

                                <div id="campaign-type-2" class="tab-pane" :class="{ 'active' : campaign.type=='ongoing'}">
                                    <h4>Ongoing Campaign</h4>
                                    <p>Maecenas metus nulla, commodo a sodales sed, dignissim pretium nunc.</p>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-sm-6">
                            <h4>Location</h4>
                            <div class="fancy-form fancy-form-select">
                                <select class="form-control" v-model="campaign.location">
                                    <option v-for="country in sortByKeyName(countries,'Name')" :value="country.CountryCodes.iso3">{{ country.Name }}</option>
                                </select>

                                <i class="fancy-arrow"></i>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <h4>Currency</h4>
                            <div class="fancy-form fancy-form-select">
                                <select class="form-control" v-model="campaign.currency">
                                    <option v-for="currency in sortByKeyName(currencies,'name')" :value="currency.code">{{ currency.name }} - {{ currency.code }}</option>
                                </select>

                                <i class="fancy-arrow"></i>
                            </div>
                        </div>
                    </div>
                    <br>

                </div>
                <div role="tabpanel" class="tab-pane " id="step2">

                    <h4>Overview</h4>
                    <div class="fancy-form">
                        <textarea rows="5" class="form-control word-count" data-maxlength="50" data-info="textarea-words-info" placeholder="Say something to summarize your project.." v-model="campaign.overview"></textarea>

                        <i class="fa fa-comments"><!-- icon --></i>

                        <span class="fancy-hint size-11 text-muted">
                            <strong>Hint:</strong> 50 words allowed!
                            <span class="pull-right">
                                <span id="textarea-words-info">0/50</span> Words
                            </span>
                        </span>

                    </div>
                    <h4>Story</h4>
                    <textarea class="summernote form-control" data-height="400" data-lang="en-US" v-model="campaign.description">asdfasd</textarea>

                    <h4>Profile Photo</h4>
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="thumbnail">
                                <img :src="campaign.profilePhoto" class="img-responsive"/>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <form id="profile-photo-uploader" action="<?=admin_url('admin-ajax.php')?>?action=uploadCampaignProfilePhoto" class="dropzone no-image nomargin" enctype="multipart/form-data">
                                <div class="fallback">
                                    <input name="photo" type="file" />
                                </div>
                            </form>
                            <small class="text-muted block">646 x 220 | Max file size: 10Mb (jpg/png/gif)</small>
                        </div>
                    </div>

                    <hr />
                    <h4>Cover Photo</h4>
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="thumbnail">
                                <img :src="campaign.coverPhoto" class="img-responsive"/>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <form id="profile-photo-uploader" action="<?=admin_url('admin-ajax.php')?>?action=uploadCampaignCoverPhoto" class="dropzone no-image nomargin" enctype="multipart/form-data">
                                <div class="fallback">
                                    <input name="photo" type="file" />
                                </div>
                            </form>
                            <small class="text-muted block">828 x 315 | Max file size: 10Mb (jpg/png/gif)</small>
                        </div>
                    </div>

                    <hr />
                    <h4>Promotional Video</h4>
                    <div class="row">
                        <div class="col-sm-12">
                            <template v-if="videoStatus">
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe class="embed-responsive-item" width="560" height="315" :src="youtubeUrl"></iframe>
                                </div><br>
                            </template>
                            <div class="fancy-form">
                                <i class="fa fa-link"></i>
                                <input type="text" class="form-control" placeholder="e.g. Youtube or Vimeo URL" v-model="campaign.video" @change="validateYouTubeUrl">
                                <span class="fancy-tooltip top-right">
                                    <em>{{ messages.video }}</em>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="step3">
                    <h4>Rewards</h4>
                    Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui.
                </div>
                <div role="tabpanel" class="tab-pane" id="step4">
                    <h4>Recipient</h4>
                    Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui.
                </div>
            </div>
        </div>
    </div>
</div>
<script>
var assets_path = "<?=DM_CROWD_PLUGIN_URL?>";
</script>
