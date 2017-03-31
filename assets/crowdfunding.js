var Events = new Vue();
// https://gist.githubusercontent.com/Fluidbyte/2973986/raw/b0d1722b04b0a737aade2ce6e055263625a0b435/Common-Currency.json


var app = new Vue({
    el: '#create-campaign',
    data: {
        campaign: {
            title : '',
            category : 'Technology',
            type : 'timed',
            startDate : '',
            endDate : '',
            goal : 100000,
            currency: 'USD',
            location : 'USA',
            video : '',
            videoID : '',
            overview : '',
            description : '',
            profilePhoto : 'https://placeholdit.imgix.net/~text?txtsize=78&txt=Profile%20Photo&w=640&h=320',
            coverPhoto : 'https://placeholdit.imgix.net/~text?txtsize=78&txt=Cover%20Photo&w=828&h=315',
        },
        messages: {
            video : 'Youtube, Vimeo'
        },

        countries: {},
        currencies: {},
        daysToGo : 0,
        videoStatus : false
    },
    computed: {

    },
    filters: {

        currency : function(n, x, c, d, t){
            // var n = this,
            var x = x == undefined ? "$" : x,
            c = isNaN(c = Math.abs(c)) ? 2 : c,
            d = d == undefined ? "." : d,
            t = t == undefined ? "," : t,
            s = n < 0 ? "-" : "",
            i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
            j = (j = i.length) > 3 ? j % 3 : 0;
            return (x + "") + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
        }
    },
    computed: {
        youtubeUrl(){
            if(!this.videoStatus) return false;

            return 'https://www.youtube.com/embed/' + this.campaign.videoID + '?autoplay=0';
        }
    },
    methods: {
        updateCampaignEndDate(){
            this.daysToGo = $('#timed-end-date').data("DateTimePicker").date().diff(moment(), 'days');
        },
        fetchCountryList(){
            var self = this;
            axios.get(assets_path + '/assets/all.json')
            .then(function (response) {
                self.countries = response.data.Results;
            })
        },
        fetchCurrencyList(){
            var self = this;
            axios.get('https://gist.githubusercontent.com/Fluidbyte/2973986/raw/b0d1722b04b0a737aade2ce6e055263625a0b435/Common-Currency.json')
            .then(function (response) {
                self.currencies = response.data;
            })
        },
        sortByKeyName(obj,key){
            var arr = [];
            var key = key == undefined ? 0 : key;
            for (var prop in obj) {
                if (obj.hasOwnProperty(prop)) {
                    arr.push( obj[prop] );
                }
            }

            arr.sort(function(a, b) { return a[key].toLowerCase().localeCompare(b[key].toLowerCase()); }); //use this to sort as strings
            return arr; // returns array
        },
        validateYouTubeUrl() {
            url  = this.campaign.video;

            if (url != undefined || url != '') {
                var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/;
                var match = url.match(regExp);
                if (match && match[2].length == 11) {
                    // Do anything for being valid
                    // if need to change the url to embed url then use below line
                    // return match[2];
                    this.videoStatus = true;
                    this.campaign.videoID = match[2];
                    return this.messages.video = 'YAY!';
                    // $('#ytplayerSide').attr('src', 'https://www.youtube.com/embed/' + match[2] + '?autoplay=0');
                }
            }

            this.videoStatus = false;
            this.videoID = false;
            return this.messages.video = 'Invalid URL';
        }
    },
    created : function(){
        var self = this;

        Events.$on('startDateChanged',function(value){
            self.campaign.startDate = value;
        });

        Events.$on('endDateChanged',function(value){
            self.updateCampaignEndDate();
            self.campaign.endDate = value;
        });

        Events.$on('profilePhotoUploaded',function(data){
            switch (data.type) {
                case 'profile':
                self.campaign.profilePhoto = data.url;
                break;
                case 'profile':
                default:
                self.campaign.coverPhoto = data.url;
                break;
            }
        });

        this.fetchCountryList();
        this.fetchCurrencyList();
    }
})


$(function () {
    $('#timed-start-date').datetimepicker();
    $('#timed-end-date').datetimepicker({
        useCurrent: false //Important! See issue #1075
    });
    $("#timed-start-date").on("dp.change", function (e) {

        $('#timed-end-date').data("DateTimePicker").minDate(e.date);
        Events.$emit('startDateChanged',$(this).find('input').first().val());

    });
    $("#timed-end-date").on("dp.change", function (e) {

        $(this).find('input').first().trigger('blur');
        $('#timed-start-date').data("DateTimePicker").maxDate(e.date);
        Events.$emit('endDateChanged',$(this).find('input').first().val());

    });

    Dropzone.options.profilePhotoUploader = {
        paramName: "image", // The name that will be used to transfer the file
        maxFilesize: 2, // MB
        filesizeBase: 1024,
        addRemoveLinks: true,
        accept: function(file, done) {
            // console.log(file,done);
            done();
        },
        init: function() {
            this.on("addedfile", function(file) {
                console.log("Added file.",file);
            });
            this.on("uploadprogress", function(file,progress,bytesSent) {
                console.log(file,progress,bytesSent);
            });
            this.on("success", function(file,response) {
                var response = JSON.parse(response);
                Events.$emit('profilePhotoUploaded',response);
                this.removeAllFiles(true);
            });
        }
    };
});
