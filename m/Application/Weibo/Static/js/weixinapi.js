var voice = {
    localId: '',
    serverId: '',
    url: '',
    translate: '',
};
// 3.1 识别音频并返回识别结果

//智能接口
$('#translateVoice').click(function () {
    if (voice.localId == '') {
        $.toast('请先使用 startRecord 接口录制一段声音');
        return;
    }
    wx.translateVoice({
        localId: voice.localId,
        complete: function (res) {
            if (res.hasOwnProperty('translateResult')) {
                alert('识别结果：' + res.translateResult);
            } else {
                alert('无法识别');
            }
        }
    });
});
$("#playVoice").attr("style", "display:none;");
$("#pauseVoice").attr("style", "display:none;");

var rMark = '' ;//控制录音动画
//按住录音
$(document).on('touchstart', '[data-role="touch-on"]', function (event) {
    $('#cModel').attr("style", "display:none;");
    $('.big').css('background-color', '#EA5033');
    $('.small').css('background-color', '#ffffff');
    $(this).find('p').html('松开结束');
    rMark = setInterval("recorddoing ()",200);
    event.preventDefault();
    START = new Date().getTime();
    recordTimer = setTimeout(function () {
        wx.startRecord({
            success: function () {
                localStorage.rainAllowRecord = 'true';
            },
            cancel: function () {
                $.toast('用户拒绝授权录音');
            }
        });
    }, 300);
});

//松手结束录音
$(document).on('touchend', '[data-role="touch-on"]', function (event) {
    $('#cModel').attr("style", "display:block;");
    $('.big').css('background-color', '#ffffff');
    $('.small').css('background-color', '#EA5033');
    $(this).find('p').html('按住录音');
    clearMark() ;
    event.preventDefault();
    END = new Date().getTime();
    if ((END - START) < 300) {
        END = 0;
        START = 0;
        //小于300ms，不录音
        clearTimeout(recordTimer);
    } else {
        wx.stopRecord({
            success: function (res) {
                voice.localId = res.localId;
                uploadVoice();
            },
            fail: function (res) {
                console.log(res);
                $.toast(JSON.stringify(res));
            }
        });
    }
});
//点击录音
var clickMark = false ;//点击录音功能标记
$(document).on('click', '[data-role="click-on"]', function (event) {
    if (clickMark) {
        clickMark = false ;
        $('#cModel').attr("style", "display:block;");
        $('.big').css('background-color', '#ffffff');
        $('.small').css('background-color', '#EA5033');
        $(this).find('p').html('点击录音');
        clearMark() ;
        event.preventDefault();
        END = new Date().getTime();
        if ((END - START) < 300) {
            END = 0;
            START = 0;
            //小于300ms，不录音
            clearTimeout(recordTimer);
        } else {
            wx.stopRecord({
                success: function (res) {
                    voice.localId = res.localId;
                    uploadVoice();
                },
                fail: function (res) {
                    console.log(res);
                    $.toast(JSON.stringify(res));
                }
            });
        }
    } else {
        clickMark = true ;
        $('#cModel').attr("style", "display:none;");
        $('.big').css('background-color', '#EA5033');
        $('.small').css('background-color', '#ffffff');
        $(this).find('p').html('点击结束');
        rMark = setInterval("recorddoing ()",200);
        event.preventDefault();
        START = new Date().getTime();
        recordTimer = setTimeout(function () {
            wx.startRecord({
                success: function () {
                    localStorage.rainAllowRecord = 'true';
                },
                cancel: function () {
                    $.toast('用户拒绝授权录音');
                }
            });
        }, 300);
    }
});
//翻译语音
function translate(callback) {
    wx.translateVoice({
        localId: voice.localId,
        complete: function (res) {
            if (res.hasOwnProperty('translateResult')) {
                voice.translate = res.translateResult;
                callback(voice);
            } else {
                voice.translate = '';
            }
        }
    });
}
$("#sendVoice").click(function () {
    if (voice.localId == '') {
        $.toast('请先录一段语音！');
    } else {
        uploadVoice(function (voice_path) {
            translate(function (res) {
                $.post(U('Weibo/Voice/send_voice'), {
                    voice: voice_path.url,
                    voiceTranslate: res.translate
                }, function (res) {
                    if (res.result == 1) {
                        $.toast('成功');
                        location.href = U('weibo/index/index');
                    } else {
                        $.toast(res.result);
                    }
                });
            });
        });
    }
});
//上传录音
function uploadVoice(callback) {
    //调用微信的上传录音接口把本地录音先上传到微信的服务器
    //不过，微信只保留3天，而我们需要长期保存，我们需要把资源从微信服务器下载到自己的服务器
    wx.uploadVoice({
        localId: voice.localId, // 需要上传的音频的本地ID，由stopRecord接口获得
        isShowProgressTips: 0, // 默认为1，显示进度提示
        success: function (res) {
            //把录音在微信服务器上的id（res.serverId）发送到自己的服务器供下载。
            $.post(U('Weibo/Voice/uploadVoliceToCloud'), {serverId: res.serverId}, function (res) {
                upload_voice = res.info;
                voice.url = res.path;
                callback(voice);
            });//把录音在微信服务器上的id（res.serverId）发送到自己的服务器供下载。
        }
    });
}
$('#playVoice').click(function () {
    if (voice.localId == '') {
        $.toast('请先录制一段声音');
        return;
    }
    else {
        $("#playVoice").html('正在播放...');
        wx.playVoice({
            localId: voice.localId
        });
    }
});
$('#pauseVoice').click(function () {
    if (voice.localId == '') {
        $.toast('请先录制一段声音');
        return;
    }
    wx.pauseVoice({
        localId: voice.localId
    });
});
wx.onVoicePlayEnd({
    complete: function (res) {
        $.toast('录音播放结束');
    }
});
wx.onVoiceRecordEnd({
    complete: function (res) {
        voice.localId = res.localId;
        $.toast('录音时间已超过一分钟');
    }
});
//录音时动画
var recordnum = 1 ;
var li = $('[data-role="ul-list"]').find('li') ;
function recorddoing () {
    if (recordnum>5){
        recordnum = 1 ;
        $.each(li ,function(){
            if ($(this).attr('data-title') == 'record-doing1') {
                return true;
            }
            $(this).removeClass('on') ;
            $(this).addClass('out') ;
        });
        return ;
    }
    $('[data-title="record-doing'+recordnum+'"]').addClass('on') ;
    $('[data-title="record-doing'+recordnum+'"]').removeClass('out') ;
    recordnum++ ;
}
//清除录音动画
function clearMark() {
    clearInterval(rMark);
    $.each(li ,function(){
        $(this).removeClass('on') ;
        $(this).addClass('out') ;
    });
    recordnum = 1 ;
}

//改变录音模式
$('[data-role="model-change"]').click(function(){
    var touchOn = $('[data-role="touch-on"]') ;
    var onClick = $('[data-role="click-on"]') ;
    var mark = '' ;
    var cmark = '' ;
    if (touchOn.length > 0) {
        $.each(touchOn, function(){
            $(this).attr('data-role', 'click-on') ;
        }) ;
        mark = '切换长按模式' ;
        cmark = '点击录音' ;
    } else if(onClick.length > 0){
        $.each(onClick, function(){
            $(this).attr('data-role', 'touch-on') ;
        }) ;
        mark = '切换点击模式' ;
        cmark = '按住录音' ;
    } else{
        return false ;
    }
    $(this).html(mark) ;
    $('.record ').html(cmark) ;
}) ;

