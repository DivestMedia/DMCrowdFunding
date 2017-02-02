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
        },
        countries: {},
        currencies: {},
        daysToGo : 0
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
            console.log(arr);
            return arr; // returns array
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
});
