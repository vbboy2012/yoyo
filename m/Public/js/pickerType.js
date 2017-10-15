/**
 * Created by Administrator on 2017/9/19 0019.
 */
//页面调用基本格式
// $(function () {
//     var type={$type};
//     onload(type,'[data-role="chose_type"]','[data-role="crowd_type"]');
// })

//分类数据查询结构
// $map['status']=1;
// $map['pid']=0;
// $type=D('NewsCategory')->where($map)->field('id,title')->select();
// foreach ($type as &$val){
//     $val['sub']=D('NewsCategory')->where(array('pid'=>$val['id'],'status'=>1))->field('id,pid,title')->select();
// }
// unset($val);
// $this->assign('type',json_encode($type));

//提交获取时
// $category=explode(',',I['post.type_id']);
// $data['category']=$category[1] ? $category[1]:$category[0];
/**
 *
 * @param type  分类数据，格式要求必须是json格式，子分类数组必须是sub
 * @param chose_type  触发方法的对象，如.class或者data——role之类；
 * @param crowd_type  隐藏input的对象
 * @author sun slf_02@ourstu.com
 */

var pickerType=function (type,chose_type,crowd_type) {
    $.fn.typePicker=function (params){
        return this.each(function() {
            if(!this) return;
            var format = function(data) {
                var result = [];
                for(var i=0;i<data.length;i++) {
                    var d = data[i];
                    if(d.id ===0) continue;
                    result.push(d.title);
                }
                if(result.length) return result;
                return [""];
            };

            var findId=function (data) {
                var id = [];
                if(!data.sub) return [''];
                for(var i=0;i<data.sub.length;i++) {
                    var d = data.sub[i];
                    if(d.id ===0) continue;
                    id.push(d.id);
                }
                if(id.length) return id;
                return [""];
            };

            var sub = function(data) {
                if(!data.sub) return [""];
                return format(data.sub);
            };

            var getCities = function(d) {
                for(var i=0;i< raw.length;i++) {
                    if(raw[i].id === d) return sub(raw[i]);
                }
                return [""];
            };

            var getTypeId=function (d) {
                for (var i=0;i<raw.length;i++){
                    if(raw[i].id === d) return findId(raw[i]);
                }
            };

            var raw = type;
            var provinces = raw.map(function(d) {
                return d.id;
            });
            var text=raw.map(function(d) {
                return d.title;
            });

            var initCities = sub(raw[0]);

            var type_id=findId(raw[0]);

            var currentProvince = provinces[0];
            var currentCity = initCities[0];

            var defaults = {

                cssClass: "city-picker",
                rotateEffect: false,  //为了性能

                onChange: function (picker, values, displayValues) {
                    var newProvince = values[0];
                    var newCity;
                    if(newProvince !== currentProvince) {
                        var newCities = getCities(newProvince);
                        var newtypeId=getTypeId(newProvince);
                        newCity = newCities[0];
                        picker.cols[1].replaceValues(newtypeId,newCities);
                        currentProvince = newProvince;
                        currentCity = newCity;
                        picker.updateValue();
                        return;
                    }
                },
                cols: [
                    {
                        values: provinces,
                        cssClass: "col-province",
                        displayValues:text
                    },
                    {
                        values: type_id,
                        cssClass: "col-city",
                        displayValues:initCities
                    }
                ]
            };

            var p = $.extend(defaults, params);
            //计算value

            $(this).picker(p);
        });

    };
    $(chose_type).typePicker({
        formatValue:function(picker, value, displayValue){
            $(crowd_type).val(value);
            return displayValue;
        },
        toolbarTemplate: '<header class="bar bar-nav">\
       <button class="button button-link pull-right close-picker">确定</button>\
       <h1 class="title">选择分类</h1>\
       </header>'
    })
}