function addSocialData(provider)
{
    var var_query = {
    "function": "addSocialData"
    };

    var var_function = [provider];

    pasarelaAjax('GET', var_query, "addSocialDataHTML", var_function);

}

function addSocialDataHTML(response, provider)
{

    var respuesta = null;
    if (response != "null")
    {
        respuesta = JSON.parse(response);

        if (respuesta[0]["message"] == "success")
        {
            $(".button-" + provider).children("img").attr("src", "/images/sociallogin/ico-cab-" + provider + "-duo.png")
            return true;
        }
        if (respuesta[0]["message"] == "duplicate")
        {
            _alert("Esta cuenta de " + provider + " ya esta relacionada con otro usuario","Error")
            return false;
        }
        if (respuesta[0]["message"] == "login")
        {
            window.location='/sociallogin.php?'+($.get('callback') ? "callback="+$.get('callback') : "");
            return false;
        }
    }
    _alert("Error de conexión favor de intentar mas tarde","Error")
    return false;
}

function removeConnection(red)
{
    var var_query = {
          "function": "removeConnection",
          "vars_ajax":[red]
    };

    var var_function = [red];

    pasarelaAjax('GET', var_query, "removeConnectionHTML", var_function);

}

function removeConnectionHTML(response, provider)
{

    var respuesta = null;
    if (response != "null")
    {
        respuesta = JSON.parse(response);

        if (respuesta[0]["message"] == "success")
        {
            $(".button-" + provider).children("img").attr("src", "/images/sociallogin/ico-cab-" + provider + ".png")
            return true;
        }

    }
    _alert("Error de conexión favor de intentar mas tarde","Error")
    return false;
}

$(document).ready(function(){
   $('#button-facebook-connect').on('click', function (e) {
   var url = "/social-login/pasarela/facebook/";
   new_window = window.open(url, 'Facebook', 'height=500,width=900,resizable=false,scrollbars=no');
   e.preventDefault();
   });

   $('#facebook_rel').on('change', function (e) {
   
        if ($(this).is(":checked")) {
            var url = "/social-login/pasarela/facebook/";
            new_window = window.open(url, 'Facebook', 'height=400,width=800,resizable=false,scrollbars=no');
            
        }
        else
        {
            removeConnection('facebook')
        }
    });

    $('#button-google-connect').on('click', function (e) {
        e.preventDefault();
    });

    $('#google_rel').on('change', function (e) {

        if ($(this).is(":checked")) {
            document.getElementById("button-google-connect").click()
           
        }
        else
        {
            removeConnection('google')
        }
    });

});
this.oauth_callback = function(result)
{
       if(result.request == "success")
       {
               addSocialData(result.provider);
       }
       else {
       }
};

function attachSigninGoogle(element) {
    console.log(element.id);
    auth2.attachClickHandler(element, {},
        function(googleUser) {
            var id_token = googleUser.getAuthResponse().id_token;
            var url = "/social-login/callback/google/?id_token="+id_token;
            new_window = window.open(url, 'Google', 'height=400,width=800,resizable=false,scrollbars=no');
        }, function(error) {
          //alert(JSON.stringify(error, undefined, 2));
        });
  }

function loadGoogleLogin() {
    gapi.load('auth2', function(){
        // Retrieve the singleton for the GoogleAuth library and set up the client.
        auth2 = gapi.auth2.init({
        client_id: $("[data-g-id]").attr('data-g-id')+'.apps.googleusercontent.com',
        cookiepolicy: 'single_host_origin',
        // Request scopes in addition to 'profile' and 'email'
        //scope: 'additional_scope'
        });
        attachSigninGoogle(document.getElementById('button-google-connect'));
    });

}