/**
 * Created by Administrator on 15-4-30.
 */





var video = {
    html:'<div class="video" style="margin-bottom:330px;position: absolute;background: #fff;z-index: 1001;border: 1px solid #ccc; padding: 0 20px;width:100%;max-width: 500px;height: 330px;">'+
    '<div style=" float: right;cursor: pointer;" onclick="video.closevideo()"><i class="iconfont icon-guanbi"></i></div>'+
    '<div id="uploader" class="wu-example" style="margin-top: 20px;">'+
        <!--用来存放文件信息-->
    '<div id="thelist" class="uploader-list">'+
    '</div>'+
    '<div class="btns">'+
    '<div id="picker" >选择文件</div>'+
    '</div>'+
    '</div>'+
    '<div><h4 id="videotitle" style="width: 100%;overflow: hidden">支持小于20M的<br/>avi,webm,3gp,rmvb,wmv,swf<br/>flv,mkv,mpeg,mpg,mov,mp4格式视频</h4></div>'+
    '<input name="feed_type" value="LocalVideo" type="hidden">'+
    '<div class="clearfix" id="show_info"></div>'+
    '<div><h3 id="videotitle"></h3></div>'+
    '<div id="my52player" style="width: 100%;height: 200px;display: none;margin-top: 10px;"></div>'+
    '</div>',
    upload_video:function(){
        $('#hook_show').html(this.html);
            var id = "#uploader";
            var uploader_video = WebUploader.create({
                // swf文件路径
                swf: 'Uploader.swf',
                // 限制大小20M
                fileSingleSizeLimit:20*1024*1024,
                // 文件接收服务端。
                server: U('Core/File/uploadVideo'),
                // 选择文件的按钮。可选。
                // 内部根据当前运行是创建，可能是input元素，也可能是flash.
                pick: id,

                // 不压缩image, 默认如果是jpeg，文件上传前会压缩一把再上传！
                resize: false,
                accept:{
                    title: 'videoUpload',
                    extensions: 'avi,webm,3gp,rmvb,wmv,swf,flv,mkv,mov,mp4' ,
                    mimeTypes: 'video/*',
                }
            });
            uploader_video.on("error",function (type){
                if (type=="Q_TYPE_DENIED"){
                 toast.error("只允许avi,webm,3gp,rmvb,wmv<br/>swf,flv,mkv,mov,mp4格式") ;
                }else if(type=="F_EXCEED_SIZE"){
                    toast.error("文件大小不能超过20M");
                }
            })
            // 当有文件被添加进队列的时候
            uploader_video.on('fileQueued', function (file) {
                uploader_video.upload();
               showWaiting($('#show_info'), 'Uploading..')
            });
            // 文件上传过程中创建进度条实时显示。
            uploader_video.on('uploadSuccess', function (file, ret) {
                removeWaiting($('#show_info'));
                if (ret.status) {
                    var path='';
                    var key='';
                    var path1=ret.data.file.savepath;
                    var path2=ret.data.file.savename;
                    var path3=path1+path2;
                    var title=ret.data.file.name;
                   // console.log(file);

                  var id = ret.data.file.persistentId;
                    if (id != null) {
                        showWaiting($('#show_info'), '视频转码中');
                        get_poefop(id, function () {
                            toast.info('转码成功');
                            removeWaiting($('#show_info'));
                            $('#my52player').show();
                            $('#videotitle').html(title);
                            $('.btn-group').show();
                            flowplayer("my52player", "./Addons/InsertLocalVideo/_static/52player/flowplayer-3.2.12.swf", {clip: { url: path1, autoPlay: false, autoBuffering: false,scaling: 'fit'} });
                            var $content = $('#weibo_content');
                            $content.html(' <br/>#视频分享# '+title+'|video:|'+path2);

                        });

                    } else {
                        var url=$('#get_root').attr('data-url');
                        var url1=$('#get_driver').attr('data-url');
                        $.post(url1,{path:path3},function (b) {
                           if(b=='local'){
                               $.post(url,{path:path3},function (b) {
                                   path=b+path3;

                                   $('#my52player').show();
                                   $('#videotitle').html(title);

                                   flowplayer("my52player", "./Addons/InsertLocalVideo/_static/52player/flowplayer-3.2.12.swf", { clip: { url:path,autoPlay: false, autoBuffering: false, scaling: 'fit'} });
                                   var $content = $('#weibo_content');
                                   $content.html(' <br/>#视频分享# '+title+'|video:|'+path2);

                               });
                           }
                            else{


                                   $('#my52player').show();
                                   $('#videotitle').html(title);

                                   flowplayer("my52player", "./Addons/InsertLocalVideo/_static/52player/flowplayer-3.2.12.swf", { clip: { url:'..'+ret.data.file.savepath+ret.data.file.savename,autoPlay: false, autoBuffering: false, scaling: 'fit'} });
                                   var $content = $('#weibo_content');
                                   $content.html(' <br/>#视频分享# '+title+'|video:|'+path2);


                           }
                        });


                  }

                } else {
                    toast.error(ret.info);
                }
                // 改变为视频状态
                $('#weibotype').val('LocalVideo');
                // 清除图片
                $("input[name='image']").val('');
                // 清除图片
                $('.img').remove();

            });
            uploader_video.on('uploadError', function (file) {
                //console.log(file);
                toast.error('上传出错。')
            });
        },
        closevideo:function (){
            var html='';
            $('#hook_show').html(html);
        },


            /* show_box: function () {

           var url= $('#chose_video').val();
            $('#player1').attr('href',url);
            /*  $('#video_url').focus();
            bind_search_video();
        },
     search_video: function ($this) {
         alert('dfdsf');
          /*  toast.showLoading();
           var url = $('#insert_video_search_url').val();
            var link = $("#video_url").val();
            $.post(url, {url:link }, function (res) {
                eval("var data=" + res);
                if (data.boolen == 1) {
                    var $hook_show = $this.closest('#hook_show');
                    var $content = $this.closest('.weibo_post_box').find('#weibo_content');
                    $content.val(' #视频分享# ' + (data.is_swf==1?'':data.data.title) +' '+  (data.is_swf==1?'':link));
                    $hook_show.find('input[name=title]').val(encodeURIComponent(data.data.title));
                    $hook_show.find('input[name=swf_src]').val(encodeURIComponent(data.data.flash_url));
                    $hook_show.find('input[name=img_url]').val(encodeURIComponent(data.data.img_url));
                    $hook_show.find('input[name=video_url]').val( data.is_swf==1?'无':link);
                    toast.success('搜索成功');
                    $('.video_s_r').html('<div>'+ (data.is_swf==1?'':data.data.title)+'</div><embed src="' + data.data.flash_url + '" wmode="transparent" allowfullscreen="true" type="application/x-shockwave-flash" style="width: 100%;height:350px;"></embed>');
                } else {
                    $('input[name=swf_src]').val('');
                    toast.error(data.message);
                }
                toast.hideLoading();
            })

        },

        close:function(obj){
            if(confirm('是否确定取消发布视频？')){
                obj.parents('#hook_show').html('');
                clear_weibo()
            }
        }*/
}
function get_poefop(id, callback) {

    //setInterval(get_poefop(id,callback),3000);

    $.get('{:addons_url("QiNiu://QiNiu/getPrefop")}', {id: id}, function (res) {
        if (res.code != 0) {
            setTimeout(function () {
                get_poefop(id, callback)
            }, 3000)
        } else {
            callback(res);
        }
    })

}
function showWaiting($obj, info) {
    info = info || '请等待...';
    var html = '<img class="pull-left" src="./Addons/InsertLocalVideo/_static/images/plane.gif"> <div class="pull-left" style="line-height: 28px;height: 28px;margin-left: 10px">'+info+'</div>';
    $obj.html(html);
}
function removeWaiting ($obj){
    $obj.html('');
}