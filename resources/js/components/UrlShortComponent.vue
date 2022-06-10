<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <h2 class="card-header">URLs Shortener</h2>

                    <div class="card-body">
                        <form method="post" @submit.prevent="shortUrl">

                            <div class="input-group mb-3">
                                <button type="button" class="btn btn-dark"><i class="fa fa-link"></i></button>
                                <input required type="text" v-model="link" name="link" class="form-control"
                                       placeholder="Enter URL">
                                <div class="input-group-append">
                                    <input type="submit" class="btn btn-success" value="Generate Short Url">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="alert alert-success" style="background-color: white; border: 0; text-align: right;">
                        <button id="all_list_button" @click.prevent="getAllShortUrls" type="button" class="btn btn-warning" style="color: saddlebrown;">Show List</button>
                    </div>


                    <div class="card-body">

                        <div id="success_message" v-if="shortUrlsData && shortUrlsData.success && shortUrlsData.message=='Shorten Url Generated Successfully!'" class="alert alert-success">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <p style="font-weight: 500; margin-bottom: 0;">{{ shortUrlsData.message }}</p>
                        </div>
                        <div id="success_message1" v-else-if="shortUrlsData && shortUrlsData.success && shortUrlsData.message=='Shorten Url Already Exist'" class="alert alert-warning">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <p style="font-weight: 500; margin-bottom: 0;">{{ shortUrlsData.message }}</p>
                        </div>

                        <!--                        start of existing_data table-->
                        <table id="existing_data" v-if="shortUrlsData && shortUrlsData.message=='Shorten Url Already Exist'" class="table table-bordered table-sm existing_data_list">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th style="width:25%">Short Url</th>
                                <th>Full Link</th>
                            </tr>
                            </thead>
                            <tbody>

                            <tr>
                                <td>{{ shortUrlsData.data.id }}</td>
                                <td><a :href="baseUrl+'/'+shortUrlsData.data.hash" target="_blank">{{ shortUrlsData.data.hash }}</a></td>
                                <td>{{ shortUrlsData.data.link }}</td>
                            </tr>
                            <!--                            <pre>-->
                            <!--    {{ shortUrlsData }}-->
                            <!--</pre>-->
                            </tbody>
                        </table>
<!--                        end of existing_data table-->


                        <br>
                        <table id="" v-if="getAllShortUrlsData && getAllShortUrlsData.length>0" class="table table-bordered table-sm all_list" style="display: none;">
                            <thead>
                            <tr>
                                <th colspan="3" style="text-align: center; color: darkslategray; background-color: lavender;">List Of Short URL</th>
                            </tr>
                            <tr>
                                <th>ID</th>
                                <th style="width:25%">Short Url</th>
                                <th>Full Link</th>
                            </tr>
                            </thead>
                            <tbody>

                            <tr v-for="getshorturls in getAllShortUrlsData" :key="getshorturls.id">
                                <td>{{ getshorturls.id }}</td>
                                <td><a :href="baseUrl+'/'+getshorturls.hash" target="_blank">{{ getshorturls.hash }}</a></td>
                                <td>{{ getshorturls.link }}</td>
                            </tr>
                            <!--                            <pre>-->
                            <!--    {{ getAllShortUrlsData }}-->
                            <!--</pre>-->
                            </tbody>
                        </table>

                        <table v-else class="table table-bordered table-sm all_list" style="display: none;">
                            <tr>
                                <td style="text-align: center; color: red;">No Record Found.</td>
                            </tr>
                        </table>

                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    // mounted() {
    //     console.log('Url Short Component mounted.')
    // },
    name: "UrlShortComponent",
    data() {
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            link: null,
            hash: null,
            shortUrlsData: {},
            getAllShortUrlsData: {},
            baseUrl: window.location.origin
        }
    },
    methods: {
        shortUrl() {
            // alert(this.csrf);die();
            if (this.link && this.link !== '') {
                axios.post('/link-short', {
                    link: this.link,
                    csrf: this.csrf
                }).then((res) => {
                    console.log(res.data);
                    console.log(this.baseUrl);
                    this.shortUrlsData = res.data;
                    this.link = null;
                        $('#success_message').show('');
                        $('#success_message1').show('');

                    // if(this.shortUrlsData.message=='Shorten Url Already Exist'){
                    //     $('#success_message').addClass('alert alert-warning');
                    //     $('#success_message').removeClass('alert alert-success');
                    // }else{
                    //     $('#success_message').addClass('alert alert-success');
                    //     $('#success_message').removeClass('alert alert-warning');
                    // }

                    setTimeout(function() {
                        $('#success_message').hide('');
                        //$('#success_message1').hide('');
                    }, 3000);

                }).catch((error) => {
                    console.log(error);
                })
            } else {
                alert('URL field is required.');
            }
        },
        getAllShortUrls() {
            // alert(1);
            axios.get('/get-short-urls', {}).then((res) => {
                $('.all_list').toggle();
                var $el = $('#all_list_button');
                $el.text($el.text() == "Hide List" ? "Show List": "Hide List");

                console.log(res.data);
                this.getAllShortUrlsData = res.data.data;
            }).catch((error) => {
                console.log(error);
            })
        },
        // goToSafeBrowsingApi(hash) {
        //     // alert(hash);
        //     axios.get(hash, {}).then((res) => {
        //         // $('.all_list').toggle();
        //         // var $el = $('#all_list_button');
        //         // $el.text($el.text() == "Hide List" ? "Show List": "Hide List");
        //
        //         console.log(res.data);
        //         // this.getAllShortUrlsData = res.data.data;
        //     }).catch((error) => {
        //         console.log(error);
        //     })
        // }

    }
}
</script>
