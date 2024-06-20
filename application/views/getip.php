<script type="text/javascript">
function GetIP(){
    const ipAPI = '//api.ipify.org/?format=json';

    Swal.queue([{
    title: 'Your public IP',
    confirmButtonText: 'Show my public IP',
    text:
        'Your public IP will be received ' +
        'via AJAX request',
    showLoaderOnConfirm: true,
    preConfirm: () => {
        return fetch(ipAPI)
        .then(response => response.json())
        .then(data => Swal.insertQueueStep(data.ip))
        .catch(() => {
            // Swal.insertQueueStep({
            // icon: 'error',
            // title: 'Unable to get your public IP'
            // })
        })
    }
    }])
}

function GetIPv4(){
  $.ajax({
      url : 'https://api.ipify.org/?format=json',
      method : 'GET'
  })
  .done(function(data){
      // alert(data.ip);
      $("#ipv4").val(data.ip);
  })
  .fail(function(){
      //Handle failure

  });
}

</script>