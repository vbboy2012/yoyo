//问题回答
$('[data-role="addAnswer"]').click(function(){
    var id = $(this).attr('data-id');
    var html = $('.sendArea').html();
    var text = html.replace(/\<img.*?data\-title\="(.*?)" .*?>/g, "$1");
    text = emojione.toShort(text);
    text = text.replace(/\<a.*?data\-text\="(.*?)" .*?<\/a>/g, "$1");
    text = text.replace(' ', '/nb');
    text = text.replace(/&nbsp;/g, '/nb');
    text = text.replace(/\<br>/g, '/br');
    text = text.replace(/\<div>(.*?)<\/div>/g, '/br$1');
    var url = U('Question/Index/addAnswer');
    $.post(url,{content:text,questionId:id},function(res){
        console.log(res);
        if (res.status==1) {
            $('[data-role="all-answer"]').prepend(res.html);
            $('.sendArea').html('');
            var answer=$('[data-role="answer"]').attr('data-total');
            answer=parseInt(answer);
            answer++;
            $('[data-role="answer"]').text(answer+'个回答');
            $('[data-role="answer"]').attr('data-total',answer);
            $('[data-role="answer"]').css('display','block');
            $.toast('回答成功');
            $('#noMore').hide();
        } else {
            $.toast(res.info);
        }
    })
});

//删除回答
$(document).on('click', '[data-role="deleteAnswer"]', function () {
    var $this = $(this);
    var AnswerId = $this.attr('data-id');
    var AnswerUid = $this.attr('data-uid');
    var $id = $('#answer_' + AnswerId);
    $.confirm('你确定要删除吗？',
        function () {
            $.post(U('Question/Index/doDelAnswer'), {answer_id: AnswerId,Answer_uid:AnswerUid}, function (msg) {
                if (msg.status) {
                    $id.remove();
                    var answer=$('[data-role="answer"]').attr('data-total');
                    answer=parseInt(answer);
                    answer--;
                    $('[data-role="answer"]').text(answer+'个回答');
                    $('[data-role="answer"]').attr('data-total',answer);
                    $.toast('删除回答成功');
                } else {
                    $.toast(msg.info);
                }
            }, 'json');
        },
        function () {
            return false;
        }
    );
});

//设置最佳答案
$(document).on('click', '[data-role="set-best"]', function () {
    var $this = $(this);
    var AnswerId = $this.attr('data-id');
    $.confirm('设置最佳答案后将无法修改，是否继续?',
        function () {
            $.post(U('Question/Index/setBest'), {answer_id: AnswerId}, function (msg) {
                if (msg.status) {
                    handleAjax(msg);
                } else {
                    $.toast(msg.info);
                }
            }, 'json');
        },
        function () {
            return false;
        }
    );
});

//删除问题
$('[data-role="deleteQuestion"]').click(function () {
    var $this=$(this)
    var id=$this.attr('data-id');
    var uid=$this.attr('data-uid');
    console.log(id);
    $.confirm('你确定要删除吗？',
        function () {
            $.post(U('Question/Index/doDelQuestion'),{id:id,uid:uid},function (res) {
                if (res.status==1){
                    $.toast(res.info);
                    setTimeout(function () {
                        location.href=res.url;
                    },1500);
                }else{
                    $.toast(res.info)
                }
            })
        },
        function () {
            return false;
        }
    );
});