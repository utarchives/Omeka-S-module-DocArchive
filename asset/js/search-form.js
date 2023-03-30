var paramObj = {};
$(document).ready( function() {
    $("#advanced-button").click(function(){
        var btn = $("#advanced-button");
        var div = $("#search-advanced");
        var text = btn.text();
        var li = $("<li>");
        li.attr("aria-hidden", true);
        if(text.indexOf("表示") !== -1){
            li.attr("class", "fa fa-caret-square-o-down");
            btn.html(li);
            btn.append("&nbsp;詳細条件を隠す");
            div.show();
        } else {
            li.attr("class", "fa fa-caret-square-o-right");
            btn.html(li);
            btn.append("&nbsp;詳細条件を表示");
            div.hide();
        }
        return false;
    });
    var createTargetLink = function () {
        var params = location.search.substr(1).split('&');
        for (var i = 0; i < params.length; i++) {
            var param = params[i].split('=');
            if (param[0] != 'group') {
                var param = params[i].split("=");
                paramObj[param[0]] = param[1];
            }
        }
    }
    createTargetLink();
    $("#search-group").click(function(){
        paramObj['group'] = 'true';
        var paramStr = "";
        for(var key in paramObj){
        	if (key == '') {
        		continue;
        	}
            paramStr += key+"="+paramObj[key]+"&";
        }
        location.href = "?"+paramStr;
        return false;
    });
    $("#media-search-button").click(function(){
        var url = location.href;
        if (location.search.length > 0) {
            url += '&media=true';
        } else {
            url += '?media=true';
        }
        location.href = url;
    });
    $("#show-detail-button").click(function(){
        var url = $(this).data('url')
        location.href = url;
    });
    $("#sort-button").click(function(){
        paramObj['sort_by'] = $('#sort-by').val();
        paramObj['sort_order'] = $('#sort-order').val();
        paramObj['display_record'] = $('#display-record').val();
        paramObj['old_per_page'] = $('#old-per-page').val();
        console.log(paramObj);
        var paramStr = "";
        for(var key in paramObj){
        	if (key == '') {
        		continue;
        	}
            paramStr += key+"="+paramObj[key]+"&";
        }
        location.href = "?"+paramStr;
        return false;
    });
    $(".item-expand-button").click(function(){
        var div = $('.parent-' + $(this).data('parent-item-id'));
        var icon = $('#expand-icon-' + $(this).data('parent-item-id'));
        var status = $('#expand-status-' + $(this).data('parent-item-id'));
        if(status.val() !== 'open'){
            icon.removeClass('fa fa-caret-square-o-right');
            icon.addClass('fa fa-caret-square-o-down');
            status.val('open');
            div.show();
        } else {
            icon.removeClass('fa fa-caret-square-o-down');
            icon.addClass('fa fa-caret-square-o-right');
            status.val('close');
            div.hide();
        }
        return false;
    });
    $("#search-form-reset").on('click', () => {
        $('#searchInput').val('');
        $('#titleInput').val('');
        $('#eraInput').val('');
        $('#creatorInput').val('');
        $('#referenceCodeInput').val('');
        $('#mediaCheck').removeAttr("checked").prop("checked", false).change();
        $('#targetAllItem').prop("checked", true).change();
        $('#targetGroup').removeAttr("checked").prop("checked", false).change();
        return false;
    });
});
