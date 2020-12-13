console.log("shubham");

function cheackAuthdata() {

    // generating fingerprint
    function initFingerprintJS(visitorId) {
        FingerprintJS.load().then(fp => {
            // The FingerprintJS agent is ready.
            // Get a visitor identifier when you'd like to.
            fp.get().then(result => {
                // This is the visitor identifier:
                const visitorId = result.visitorId;
                return visitorId;
            });
            // return visitorId;
        });
    }
    // initFingerprintJS();
    function ckToken() {
        var token = localStorage.getItem('token');
        var dev_id = initFingerprintJS()
        var controller = "/controller/auth-controller.php"
        console.log(controller);
        console.log(dev_id);
        if (token) {
           var dev_id = initFingerprintJS()
           jquery.ajax({
               type:'POST',
               url : controller,
               data : {"dev_id": dev_id,"token":token},
               success : function(data){
               },
               error : function(){
                   console.log("ajax Error");
               }
           })
        }
        else {
            $('#adminloading').hide(300);
        }
    }
    ckToken();
    
}


