$(document).ready(function () {
   $.ajax({
       url: 'attemptGiveUp.php', 
       method: 'GET',
       success: function (data) {
           var attempts = parseInt(data);
           if (attempts > 1) {
               $('.giveupButton').show();
           }
       },
       error: function () {
           console.error('Error fetching data.');
       }
   });
});
