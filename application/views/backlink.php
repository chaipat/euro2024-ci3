<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {

    var queryString = window.location.search;
    var urlParams = new URLSearchParams(queryString);
    var bcid = urlParams.get('_bcid');

    // console.log('queryString:' + queryString);
    // console.log('bcid:' + bcid);

    if(bcid !== null){

        GetIPv4();
        console.log('Call send_bl:' + bcid);
        send_bl(bcid);
    }
});

function send_bl(bcid){
    var url = '<?php echo _HOST_BACKLINK ?>api/v1/backlinks/check/' + bcid;
    // var url = 'service/backlink/' + bcid;
    <?php $cur_date = date('Y-m-d H:i:s'); ?>
    // var visit_at = moment().format('YYYY-MM-DD H:mm:ss');
    var visit_at = '<?php echo $cur_date ?>';
    var user_agent = navigator.userAgent;
    var myip = '<?php echo myip() ?>';
    var ip_address = $("#ipv4").val();
    var referer = '<?php echo (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : '' ?>';
    // var referer = '';
    var res_data;

    if(document.referrer){
        referer = document.referrer;
        console.log("referer: " + referer);
    }
    if(ip_address === ''){
        ip_address = myip;
    }
    // console.log("visit_at: " + visit_at);
    // console.log("user_agent: " + user_agent);
    // console.log("myip: " + myip);
    console.log("ip: " + ip_address);
    
    axios.post( url, {
        visit_at: visit_at, 
        user_agent: user_agent, 
        ip_address:ip_address, 
        referer:referer
    })
    .then(function (response) {

        // var res_data = JSON.parse(response);

        // if (typeof res_data.success === 'undefined') {
        //     console.log('parse undefined');
        //     res_data = JSON.stringify(response);
        // }
        // console.log(res_data);
        // var message = response.message;
        // if(message == '') message = 'ยินดีด้วยคุณทำสำเร็จแล้ว !!!';
        var message = 'ยินดีด้วยคุณทำสำเร็จแล้ว !!!';

        if(response.data.data.visit_count === 1){

            SuccessAlert(message);
            console.log('Response success first send_bl');
            console.log(response);
        }else if(referer != '' && ip_address != ''){
            
            SuccessAlert(message + ' ' + response.data.data.visit_count );
            console.log('Response success send_bl:' + response.data.visit_count);
            console.log(response);
        }else{

            console.log('Response visit_count != 1 send_bl:');
            console.log(response);
        }
    })
    .catch(function (error) {
        
        ErrorAlert(error.message);
        console.log(error);
    });

}
</script>