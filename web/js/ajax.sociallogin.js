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
   $('.button-facebook').on('click', function (e) {
   var url = "/social-login/pasarela/facebook/";
   new_window = window.open(url, 'Facebook', 'height=500,width=900,resizable=false,scrollbars=no');
   e.preventDefault();
   });

   $('#facebook_rel').on('change', function (e) {
        if (this.checked) {

            var url = "/social-login/pasarela/facebook/";
            new_window = window.open(url, 'Facebook', 'height=400,width=800,resizable=false,scrollbars=no');
            e.preventDefault();
        }
        else
        {

            removeConnection('facebook')
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
