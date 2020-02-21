
        <?php //echo stream() ?>
        
        <?php
            function stream(){
                return '<iframe width="100%" height="500" src="http://m3ulink.com:6969/qaiser.saeed251-restream/fZDI5HK4/405" ></iframe>';
            }        
        ?>
<html>
    <head>
        <title>hello</title>
    </head>
    <body>
    <object classid="clsid:9BE31822-FDAD-461B-AD51-BE1D1C159921" width="640" height="360" id="vlc" events="True">
  <param name="MRL" value="" />
  <param name="ShowDisplay" value="True" />
  <param name="AutoLoop" value="False" />
  <param name="AutoPlay" value="False" />
  <param name="Volume" value="50" />
  <param name="toolbar" value="true" />
  <param name="StartTime" value="0" />
  <EMBED pluginspage="http://www.videolan.org"
    type="application/x-vlc-plugin"
    version="VideoLAN.VLCPlugin.2"
    width="640"
    height="360"
    toolbar="true"
    loop="false"
    text="Waiting for video"
    name="vlc">
  </EMBED>
  <script>
      function getVLC(name)
{
    if (window.document[name])
    {
        return window.document[name];
    }
    if (navigator.appName.indexOf("Microsoft Internet")==-1)
    {
        if (document.embeds && document.embeds[name])
            return document.embeds[name];
    }
    else // if (navigator.appName.indexOf("Microsoft Internet")!=-1)
    {
        return document.getElementById(name);
    }
}

var vlc = getVLC("vlc");

  </script>
</object>
    </body>
</html>