{__NOLAYOUT__}<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>跳转提示</title>
    <script type="text/javascript" src="__ADMIN__/style/jquery.min.js"></script>
    <script type="text/javascript" src="__ADDONS__/layer/layer.js"></script>
</head>
<body>
    <input type="hidden" id="msg" name="msg" value="<?php echo(strip_tags($msg));?>">
    <input type="hidden" id="url" name="url" value="<?php echo($url);?>">
    <input type="hidden" id="wait" name="wait" value="<?php echo($wait);?>">
    <script type="text/javascript">
        (function(){
            var msg=$("#msg").val();
            var url=$("#url").val();
            var wait=$("#wait").val();
            layer.open({
                offset: ['200px','600px'],
                content: msg,
                yes: function(index, layero){
                location.href = url;
                layer.close(index);
                }
            });
            var interval = setInterval(function(){
                var time = --wait;
                if(time <= 0) {
                    location.href = url;
                    clearInterval(interval);
                };
            }, 1000);
        })();
    </script>
</body>
</html>
