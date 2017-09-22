/**
 * Created by Administrator on 2016/5/23 0023.
 */

var video = {
    html:'<div class="video" style="position: absolute;background: #fff;z-index: 1001;border: 1px solid #ccc;margin-left: 15px; padding: 0 20px;width:90%;height:150px">'+
    '<div style=" float: right;cursor: pointer;" onclick="video.closevideo()"><i class="icon icon-times"></i></div>'+
    '<div id="uploader" class="wu-example" style="margin-top: 20px;">'+
    <!--用来存放文件信息-->
    '<div id="thelist" class="uploader-list">'+
    '</div>'+
    '<div class="btns">'+
    '<div id="picker" >选择文件</div>'+
    '</div>'+
    '</div>'+
    '<div><h3 id="videotitle">只支持播放mp4格式视频</h3></div>'+
    '<div class="clearfix" id="show_info"></div>'+
    '<div id="my52player" style="width: 450px;height: 200px;display: none;margin-top: 10px;"></div>'+
    '</div>',
    upload_video:function(){
        $('#show').html(this.html);

        var id = "#uploader";
        var uploader_video = WebUploader.create({
            // swf文件路径
            swf: 'Uploader.swf',
            // 文件接收服务端。
            server: U('Core/File/uploadFile'),
            // 选择文件的按钮。可选。
            // 内部根据当前运行是创建，可能是input元素，也可能是flash.
            pick: id,

            // 不压缩image, 默认如果是jpeg，文件上传前会压缩一把再上传！
            resize: false,
            accept:{
                extensions: 'avi,rmvb,wmv,swf,flv,rm,mkv,mpeg,mpg,mov,mp4'
            }
        });
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
                        $('#content').val(' #视频分享# '+title+'|video:|'+path2);
                        $('#weibotype').val('LocalVideo');
                       // flowplayer("my52player", "./Application/Mob/Static/js/52player/flowplayer-3.2.12.swf", {clip: { url: path1, autoPlay: false, autoBuffering: false,scaling: 'fit'} });


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
                                $('#content').val(' #视频分享# '+title+'|video:|'+path2);
                                $('#weibotype').val('LocalVideo');


                                //   flowplayer("my52player", "./Application/Mob/Static/js/52player/flowplayer-3.2.12.swf", { clip: { url:path,autoPlay: false, autoBuffering: false, scaling: 'fit'} });




                            });
                        }
                        else{
                            $('#my52player').show();
                            $('#videotitle').html(title);
                            $('#content').val(' #视频分享# '+title+'|video:|'+path2);
                            $('#weibotype').val('LocalVideo');

                            // flowplayer("my52player", "./Application/Mob/Static/js/52player/flowplayer-3.2.12.swf", { clip: { url:ret.data.file.savepath,autoPlay: false, autoBuffering: false, scaling: 'fit'} });



                        }
                    });


                }

            } else {
                toast.error(ret.info);
            }


        });
        uploader_video.on('uploadError', function (file) {
            //console.log(file);
            toast.error('上传出错。')
        });
    },
    closevideo:function (){
        var html='';
        $('#show').html(html);
    },

   
}
function get_poefop(id, callback) {

   

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
    var html = '<img class="pull-left" src="./Application/Mob/Static/images/plane.gif"> <div class="pull-left" style="line-height: 28px;height: 28px;margin-left: 10px">'+info+'</div>';
    $obj.html(html);
}
function removeWaiting ($obj){
    $obj.html('');
}