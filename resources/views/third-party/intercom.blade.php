<script>
    (function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/pqt7dlbm';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()
    document.addEventListener("DOMContentLoaded", function(event) {
        var isIntercom = false
        document.getElementById('toggleBeacon') && document.getElementById('toggleBeacon').addEventListener('click', function () {
            if (isIntercom) {
                Intercom('hide');
                isIntercom = false;
            } else {
                Intercom('show');
                isIntercom = true;
            }
        });
    });
</script>