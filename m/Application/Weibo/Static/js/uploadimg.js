/**
 * Created by Administrator on 2017/8/31 0031.
 */
var is_weixin = function() {
    var ua = navigator.userAgent.toLowerCase();
    if(ua.match(/MicroMessenger/i)=="micromessenger") {
        return true;
    } else {
        return false;
    }
};
var is_android = function(){
    var u = navigator.userAgent;
    if(u.indexOf('Android') > -1 || u.indexOf('Linux') > -1){
        return true;
    } else {
        return false;
    }
};
//移除上传操作
function removeLi(li,file_id) {
    console.log(li)
    upAttachVal('remove', file_id, $('#image'))
    $(li).parent('.waitbox').remove();
}
//新上传图片
function add_img() {
    var limit = arguments[0] ? arguments[0] : false;
    console.log(limit) ;
    // $('#weibotype').val('image');
    var filechooser = document.getElementById("choose");
    $("#upload").on("click", function () {
        filechooser.click();
    });
    filechooser.onchange = function () {
        if (!this.files.length) return;
        console.log($('.waitbox.img-btn-sit')) ;
        if (limit != false && $('.waitbox.img-btn-sit').length >= limit) {
            $.toast("只能上传"+limit+"张图片~");
            return;
        }
        var files = Array.prototype.slice.call(this.files);
        if (files.length > 9) {
            alert("最多同时只可上传9张图片");
            return;
        }
        files.forEach(function (files, i) {
            if (!/\/(?:jpeg|png|gif)/i.test(files.type)){
                $.toast('上传图片格式不符！');
            }
            var div = ' <div class="waitbox loadingBox img-btn-sit">\
                            <img src= ' + _LOADING_ + '> \
                            </div> ';
            $('.add-img-btn').before(div);
            lrz(files, {
                width: 1200,
                height: 900,
                before: function () {
                    console.log('压缩开始');
                },
                fail: function (err) {
                    console.error(err);
                },
                always: function () {
                    console.log('压缩结束');
                },
                done: function (results) {
                    // 你需要的数据都在这里，可以以字符串的形式传送base64给服务端转存为图片。
                    var data=results.base64;
                    upload(data);
                }
            });
        })
    }
}
//图片上传，返回id ,地址
function upload(data) {
    var dataUrl = U('Core/File/uploadPictureBase64');
    $.post(dataUrl, {data: data}, function (msg) {
        if (msg.status == 1) {
            var ids = $('#img_ids').val();
            upAttachVal('add', msg.id, $('#image'));
            //上传成功显示图片
            var div = ' <div class="waitbox img-btn-sit">\
                    <img src= ' + msg.path + '> \
                    <a class="del-img-btn" onclick="removeLi(this, '+msg.id+')" data-id="26"><i class="iconfont icon-guanbi2"></i></a>\
                    </div> ';
            $('.loadingBox').remove();
            $('.add-img-btn').before(div);
        } else {
            toast.error(msg.info);
        }
    }, 'json')
}
//拼接图片ID
function upAttachVal(type, attachId, obj) {
    var $attach_ids = obj;
    var attachVal = $attach_ids.val();
    var attachArr = attachVal.split(',');
    var newArr = [];

    for (var i in attachArr) {
        if (attachArr[i] !== '' && attachArr[i] !== attachId.toString()) {
            newArr.push(attachArr[i]);
        }
    }
    type === 'add' && newArr.push(attachId);
    if (newArr.length <= 9) {
        $attach_ids.val(newArr.join(','));
        return newArr;
    } else {
        return false;
    }

}

//单图上传
function add_one_img() {
    var limit = arguments[0] ? arguments[0] : 9;
    var filechooser = document.getElementById("chooseOne");
    $("#upload").on("click", function () {
        filechooser.click();
    })
    filechooser.onchange = function () {
        if (!this.files.length) return;
        var files = Array.prototype.slice.call(this.files);
        if (files.length > limit) {
            alert("最多同时只可上传"+limit+"张图片");
            return;
        }
        files.forEach(function (files, i) {
            lrz(files, {
                width: 1200,
                height: 900,
                before: function () {
                    console.log('压缩开始');
                },
                fail: function (err) {
                    console.error(err);
                },
                always: function () {
                    console.log('压缩结束');
                },
                done: function (results) {
                    // 你需要的数据都在这里，可以以字符串的形式传送base64给服务端转存为图片。
                    var data=results.base64;
                    upload(data);
                }
            });
        })
    }
    //图片上传，返回id ,地址
    function upload(data) {
        console.log(data);
        var dataUrl = U('Core/File/uploadPictureBase64');
        $.post(dataUrl, {data: data}, function (msg) {
            if (msg.status == 1) {
                console.log(msg);
                //上传成功显示图片
                var ids = $('#one_img_id').val(msg.id);
                if (!msg.id == null) {
                    $('.show_cover').hide();
                } else {
                    $('.show_cover').show();
                }
                $("#cover_url").html('');
                $("#cover_url").html('<img src="' + msg.path + '"style="width:72px;height:72px"  data-role="issue_cover" >');
            } else {
                toast.error(msg.info);
            }
        }, 'json')
    }
}