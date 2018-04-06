$(window).load(function () {
    $("#loading").fadeOut("slow");
});
function cssStyle() {
    if($('#container').hasClass('bblue'))
        $('#container').removeClass('bblue');
    if($('#container').hasClass('blightGrey'))
        $('#container').removeClass('blightGrey');
    if($('#container').hasClass('bblack'))
        $('#container').removeClass('bblack');
    if ($.cookie('the_style') == 'light') {
        $('link[href="'+site.base_url+'themes/'+site.settings.theme+'/assets/styles/blue.css"]').attr('disabled', 'disabled');
        $('link[href="assets/styles/blue.css"]').remove();
        $('<link>')
        .appendTo('head')
        .attr({type: 'text/css', rel: 'stylesheet'})
        .attr('href', site.base_url+'themes/'+site.settings.theme+'/assets/styles/light.css');
        $('#container').addClass('blightGrey');
    }
    else if ($.cookie('the_style') == 'blue') {
        $('link[href="'+site.base_url+'themes/'+site.settings.theme+'/assets/styles/light.css"]').attr('disabled', 'disabled');
        $('link[href="'+site.base_url+'themes/'+site.settings.theme+'/assets/styles/light.css"]').remove();
        $('<link>')
        .appendTo('head')
        .attr({type: 'text/css', rel: 'stylesheet'})
        .attr('href', ''+site.base_url+'themes/'+site.settings.theme+'/assets/styles/blue.css');
        $('#container').addClass('bblue');
    }
    else {
        $('link[href="'+site.base_url+'themes/'+site.settings.theme+'/assets/styles/light.css"]').attr('disabled', 'disabled');
        $('link[href="'+site.base_url+'themes/'+site.settings.theme+'/assets/styles/blue.css"]').attr('disabled', 'disabled');
        $('link[href="'+site.base_url+'themes/'+site.settings.theme+'/assets/styles/light.css"]').remove();
        $('link[href="'+site.base_url+'themes/'+site.settings.theme+'/assets/tyles/blue.css"]').remove();
        $('#container').addClass('bblack');
    }

    if($('#sidebar-left').hasClass('minified')) {
        //bootbox.alert('Unable to fix minified sidebar');
        //$.cookie('the_fixed', 'no');
        $('#content, #sidebar-left, #header').removeAttr("style");
        $('#fixedText').text('Fixed');
        $('#main-menu-act').addClass('full visible-md visible-lg').show();
        $('#fixed').removeClass('fixed');
    } else {
        if(site.settings.rtl == 1) {
            $.cookie('the_fixed', 'no');
        }
        if ($.cookie('the_fixed') == 'yes') {
            $('#content').css('margin-left', $('#sidebar-left').outerWidth(true)).css('margin-top', '40px');
            $('#sidebar-left').css('position', 'fixed').css('top', '40px').css('bottom', '40px').css('height', $(window).height()- 80).css('padding', '10px');
            $('#header').css('position', 'fixed').css('top', '0').css('width', '100%');
            $('#fixedText').text('Static');
            $('#main-menu-act').removeAttr("class").hide();
            $('#fixed').addClass('fixed');
            $("#sidebar-left").css("overflow","hidden");
            $('#sidebar-left').perfectScrollbar({suppressScrollX: true});
        } else {
            $('#content, #sidebar-left, #header').removeAttr("style");
            $('#fixedText').text('Fixed');
            $('#main-menu-act').addClass('full visible-md visible-lg').show();
            $('#fixed').removeClass('fixed');
            $('#sidebar-left').perfectScrollbar('destroy');
        }
    }
    widthFunctions();
}
$('#csv_file').change(function(e) {
    v = $(this).val();
    if (v != '') {
        var validExts = new Array(".csv");
        var fileExt = v;
        fileExt = fileExt.substring(fileExt.lastIndexOf('.'));
        if (validExts.indexOf(fileExt) < 0) {
            e.preventDefault();
            bootbox.alert("Invalid file selected. Only .csv file is allowed.");
            $(this).val(''); $(this).fileinput('clear');
            $('form[data-toggle="validator"]').bootstrapValidator('updateStatus', 'csv_file', 'NOT_VALIDATED');
            return false;
        }
        else
            return true;
    }
});


function init_img() {
    !function(e){var i='{preview}\n<div class="input-group {class}">\n   {caption}\n   <div class="input-group-btn">\n       {remove}\n       {upload}\n       {browse}\n   </div>\n</div>',t="{preview}\n{remove}\n{upload}\n{browse}\n",n='<div class="file-preview {class}">\n   <div class="close fileinput-remove text-right"><i class="fa fa-2x">&times;</i></div>\n   <div class="file-preview-thumbnails"></div>\n   <div class="clearfix"></div>   <div class="file-preview-status text-center text-success"></div>\n</div>',a='<div tabindex="-1" class="form-control file-caption {class}">\n   <div class="file-caption-name"></div>\n</div>',r='<div id="{id}" class="modal fade">\n  <div class="modal-dialog modal-lg">\n    <div class="modal-content">\n      <div class="modal-header">\n        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i></button>\n        <h3 class="modal-title">Detailed Preview <small>{title}</small></h3>\n      </div>\n      <div class="modal-body">\n        <textarea class="form-control" style="font-family:Monaco,Consolas,monospace; height: {height}px;" readonly>{body}</textarea>\n      </div>\n    </div>\n  </div>\n</div>\n',l='<div class="file-preview-frame" id="{previewId}">\n   {content}\n</div>\n',o='<div class="file-preview-frame" id="{previewId}">\n   <div class="file-preview-text" title="{caption}">\n       {strText}\n   </div>\n</div>\n',s='<div class="file-preview-frame" id="{previewId}">\n   <div class="file-preview-other">\n       {caption}\n   </div>\n</div>',p=function(i,t){return null===i||void 0===i||i==[]||""===i||t&&""===e.trim(i)},c=Array.isArray||function(e){return"[object Array]"===Object.prototype.toString.call(e)},d=function(i,t,n){return p(i)||p(i[t])?n:e(i[t])},m=function(e,i){return"undefined"!=typeof e?e.match("image.*"):i.match(/\.(gif|png|jpe?g)$/i)},v=function(e,i){return"undefined"!=typeof e?e.match("text.*"):i.match(/\.(txt|md|csv|htm|html|php|ini)$/i)},u=function(){return Math.round((new Date).getTime()+100*Math.random())},f=function(){return window.File&&window.FileReader&&window.FileList&&window.Blob},w=window.URL||window.webkitURL,g=function(i,t){this.$element=e(i),f()?(this.init(t),this.listen()):this.$element.removeClass("file-loading")};g.prototype={constructor:g,init:function(e){var n=this;n.reader=null,n.showCaption=e.showCaption,n.showPreview=e.showPreview,n.maxFileSize=e.maxFileSize,n.maxFileCount=e.maxFileCount,n.msgSizeTooLarge=e.msgSizeTooLarge,n.msgFilesTooMany=e.msgFilesTooMany,n.msgFileNotFound=e.msgFileNotFound,n.msgFileNotReadable=e.msgFileNotReadable,n.msgFilePreviewAborted=e.msgFilePreviewAborted,n.msgFilePreviewError=e.msgFilePreviewError,n.msgValidationError=e.msgValidationError,n.msgErrorClass=e.msgErrorClass,n.initialDelimiter=e.initialDelimiter,n.initialPreview=e.initialPreview,n.initialCaption=e.initialCaption,n.initialPreviewCount=e.initialPreviewCount,n.initialPreviewContent=e.initialPreviewContent,n.overwriteInitial=e.overwriteInitial,n.showRemove=e.showRemove,n.showUpload=e.showUpload,n.captionClass=e.captionClass,n.previewClass=e.previewClass,n.mainClass=e.mainClass,n.mainTemplate=p(e.mainTemplate)?n.showCaption?i:t:e.mainTemplate,n.previewTemplate=n.showPreview?e.previewTemplate:"",n.previewGenericTemplate=e.previewGenericTemplate,n.previewImageTemplate=e.previewImageTemplate,n.previewTextTemplate=e.previewTextTemplate,n.previewOtherTemplate=e.previewOtherTemplate,n.captionTemplate=e.captionTemplate,n.browseLabel=e.browseLabel,n.browseIcon=e.browseIcon,n.browseClass=e.browseClass,n.removeLabel=e.removeLabel,n.removeIcon=e.removeIcon,n.removeClass=e.removeClass,n.uploadLabel=e.uploadLabel,n.uploadIcon=e.uploadIcon,n.uploadClass=e.uploadClass,n.uploadUrl=e.uploadUrl,n.msgLoading=e.msgLoading,n.msgProgress=e.msgProgress,n.msgSelected=e.msgSelected,n.previewFileType=e.previewFileType,n.wrapTextLength=e.wrapTextLength,n.wrapIndicator=e.wrapIndicator,n.isError=!1,n.isDisabled=n.$element.attr("disabled")||n.$element.attr("readonly"),p(n.$element.attr("id"))&&n.$element.attr("id",u()),"undefined"==typeof n.$container?n.$container=n.createContainer():n.refreshContainer(),n.$captionContainer=d(e,"elCaptionContainer",n.$container.find(".file-caption")),n.$caption=d(e,"elCaptionText",n.$container.find(".file-caption-name")),n.$previewContainer=d(e,"elPreviewContainer",n.$container.find(".file-preview")),n.$preview=d(e,"elPreviewImage",n.$container.find(".file-preview-thumbnails")),n.$previewStatus=d(e,"elPreviewStatus",n.$container.find(".file-preview-status"));var a=n.initialPreview;n.initialPreviewCount=c(a)?a.length:a.length>0?a.split(n.initialDelimiter).length:0,n.initPreview(),n.original={preview:n.$preview.html(),caption:n.$caption.html()},n.options=e,n.$element.removeClass("file-loading")},listen:function(){var i=this,t=i.$element,n=i.$captionContainer,a=i.$btnFile;t.on("change",e.proxy(i.change,i)),a.on("click",function(){i.clear(!1),n.focus()}),e(t[0].form).on("reset",e.proxy(i.reset,i)),i.$container.on("click",".fileinput-remove:not([disabled])",e.proxy(i.clear,i))},refresh:function(i){var t=this,n=arguments.length?e.extend(t.options,i):t.options;t.init(n)},initPreview:function(){var e=this,i="",t=e.initialPreview,n=e.initialPreviewCount,a=e.initialCaption.length,r="preview-"+u(),l=a>0?e.initialCaption:e.msgSelected.replace("{n}",n);if(c(t)&&n>0){for(var o=0;n>o;o++)r+="-"+o,i+=e.previewGenericTemplate.replace("{previewId}",r).replace("{content}",t[o]);n>1&&0==a&&(l=e.msgSelected.replace("{n}",n))}else{if(!(n>0))return a>0?(e.$caption.html(l),void e.$captionContainer.attr("title",l)):void 0;for(var s=t.split(e.initialDelimiter),o=0;n>o;o++)r+="-"+o,i+=e.previewGenericTemplate.replace("{previewId}",r).replace("{content}",s[o]);n>1&&0==a&&(l=e.msgSelected.replace("{n}",n))}e.initialPreviewContent=i,e.$preview.html(i),e.$caption.html(l),e.$captionContainer.attr("title",l),e.$container.removeClass("file-input-new")},clear:function(e){var i=this;if(e&&e.preventDefault(),i.reader instanceof FileReader&&i.reader.abort(),i.$element.val(""),i.resetErrors(!0),e!==!1&&(i.$element.trigger("change"),i.$element.trigger("fileclear")),i.overwriteInitial&&(i.initialPreviewCount=0),i.overwriteInitial||p(i.initialPreviewContent)){i.$preview.html("");var t=!i.overwriteInitial&&i.initialCaption.length>0?i.original.caption:"";i.$caption.html(t),i.$captionContainer.attr("title",""),i.$container.removeClass("file-input-new").addClass("file-input-new")}else i.showFileIcon(),i.$preview.html(i.original.preview),i.$caption.html(i.original.caption),i.$container.removeClass("file-input-new");i.hideFileIcon(),i.$element.trigger("filecleared"),i.$captionContainer.focus()},reset:function(){var e=this;e.clear(!1),e.$preview.html(e.original.preview),e.$caption.html(e.original.caption),e.$container.find(".fileinput-filename").text(""),e.$element.trigger("filereset"),e.initialPreview.length>0&&e.$container.removeClass("file-input-new")},disable:function(){var e=this;e.isDisabled=!0,e.$element.attr("disabled","disabled"),e.$container.find(".kv-fileinput-caption").addClass("file-caption-disabled"),e.$container.find(".btn-file, .fileinput-remove, .kv-fileinput-upload").attr("disabled",!0)},enable:function(){var e=this;e.isDisabled=!1,e.$element.removeAttr("disabled"),e.$container.find(".kv-fileinput-caption").removeClass("file-caption-disabled"),e.$container.find(".btn-file, .fileinput-remove, .kv-fileinput-upload").removeAttr("disabled")},hideFileIcon:function(){this.overwriteInitial&&this.$captionContainer.find(".kv-caption-icon").hide()},showFileIcon:function(){this.$captionContainer.find(".kv-caption-icon").show()},resetErrors:function(e){var i=this,t=i.$previewContainer.find(".kv-fileinput-error");i.isError=!1,e?t.fadeOut("slow"):t.remove()},showError:function(e,i,t,n){var a=this,r=a.$previewContainer.find(".kv-fileinput-error");return p(r.attr("class"))?a.$previewContainer.append('<div class="kv-fileinput-error '+a.msgErrorClass+'">'+e+"</div>"):r.html(e),r.hide(),r.fadeIn(800),a.$element.trigger("fileerror",[i,t,n]),a.$element.val(""),!0},errorHandler:function(e,i){var t=this;switch(e.target.error.code){case e.target.error.NOT_FOUND_ERR:t.addError(t.msgFileNotFound.replace("{name}",i));break;case e.target.error.NOT_READABLE_ERR:t.addError(t.msgFileNotReadable.replace("{name}",i));break;case e.target.error.ABORT_ERR:t.addError(t.msgFilePreviewAborted.replace("{name}",i));break;default:t.addError(t.msgFilePreviewError.replace("{name}",i))}},loadImage:function(i,t){var n=this,a=e(document.createElement("img"));a.attr({src:w.createObjectURL(i),"class":"file-preview-image",title:t,alt:t,onload:function(){w.revokeObjectURL(a.src)}}),a.width()>=n.$preview.width()&&a.attr({width:"100%",height:"auto"});var r=e(document.createElement("div")).append(a);return r.html()},readFiles:function(e){function i(b){if(b>=h)return o.removeClass("loading"),void s.html("");var C=g+"-"+b,$=e[b],F=$.name,T=m($.type,$.name),y=v($.type,$.name),x=($.size?$.size:0)/1e3;if(x=x.toFixed(2),t.maxFileSize>0&&x>t.maxFileSize){var I=t.msgSizeTooLarge.replace("{name}",F).replace("{size}",x).replace("{maxSize}",t.maxFileSize);return void(t.isError=t.showError(I,$,C,b))}a.length>0&&("any"==d?T||y:"text"==d?y:T)&&"undefined"!=typeof FileReader?(s.html(p.replace("{index}",b+1).replace("{files}",h)),o.addClass("loading"),l.onerror=function(e){t.errorHandler(e,F)},l.onload=function(e){var i="",n="";if(y){var l=e.target.result;if(l.length>f){var o=u(),s=.75*window.innerHeight,n=r.replace("{id}",o).replace("{title}",F).replace("{body}",l).replace("{height}",s);w=w.replace("{title}",F).replace("{dialog}","$('#"+o+"').modal('show')"),l=l.substring(0,f-1)+w}i=t.previewTextTemplate.replace("{previewId}",C).replace("{caption}",F).replace("{strText}",l)+n}else i=t.previewImageTemplate.replace("{previewId}",C).replace("{content}",t.loadImage($,F));a.append("\n"+i)},l.onloadend=function(){var e=c.replace("{index}",b+1).replace("{files}",h).replace("{percent}",100).replace("{name}",$.name);setTimeout(function(){s.html(e)},1e3),setTimeout(function(){i(b+1)},1500),n.trigger("fileloaded",[$,C,b])},l.onprogress=function(e){if(e.lengthComputable){var i=parseInt(e.loaded/e.total*100,10),t=c.replace("{index}",b+1).replace("{files}",h).replace("{percent}",i).replace("{name}",$.name);setTimeout(function(){s.html(t)},1e3)}},y?l.readAsText($):l.readAsArrayBuffer($)):(a.append("\n"+t.previewOtherTemplate.replace("{previewId}",C).replace("{caption}",F)),n.trigger("fileloaded",[$,C,b]),setTimeout(i(b+1),1e3))}this.reader=new FileReader;var t=this,n=t.$element,a=t.$preview,l=t.reader,o=t.$previewContainer,s=t.$previewStatus,p=t.msgLoading,c=t.msgProgress,d=(t.msgSelected,t.previewFileType),f=parseInt(t.wrapTextLength),w=t.wrapIndicator,g="preview-"+u(),h=e.length;i(0)},change:function(e){var i,t=this,n=t.$element,a=n.val().replace(/\\/g,"/").replace(/.*\//,""),r=0,l=t.$preview,o=n.get(0).files,s=t.msgSelected,c=p(o)?1:o.length+t.initialPreviewCount;if(t.hideFileIcon(),i=void 0===e.target.files?e.target&&e.target.value?[{name:e.target.value.replace(/^.+\\/,"")}]:[]:e.target.files,0!==i.length){t.resetErrors(),l.html(""),t.overwriteInitial||l.html(t.initialPreviewContent);var r=i.length;if(t.maxFileCount>0&&r>t.maxFileCount){var d=t.msgFilesTooMany.replace("{m}",t.maxFileCount).replace("{n}",r);return t.isError=t.showError(d,null,null,null),t.$captionContainer.find(".kv-caption-icon").hide(),t.$caption.html(t.msgValidationError),void t.$container.removeClass("file-input-new")}t.readFiles(o),t.reader=null;var m=c>1?s.replace("{n}",c):a;t.isError?(t.$captionContainer.find(".kv-caption-icon").hide(),m=t.msgValidationError):t.showFileIcon(),t.$caption.html(m),t.$captionContainer.attr("title",m),t.$container.removeClass("file-input-new"),n.trigger("fileselect",[c,a])}},initBrowse:function(e){var i=this;i.$btnFile=e.find(".btn-file"),i.$btnFile.append(i.$element)},createContainer:function(){var i=this,t=e(document.createElement("span")).attr({"class":"file-input file-input-new"}).html(i.renderMain());return i.$element.before(t),i.initBrowse(t),t},refreshContainer:function(){var e=this,i=e.$container;i.before(e.$element),i.html(e.renderMain()),e.initBrowse(i)},renderMain:function(){var e=this,i=e.previewTemplate.replace("{class}",e.previewClass),t=e.isDisabled?e.captionClass+" file-caption-disabled":e.captionClass,n=e.captionTemplate.replace("{class}",t+" kv-fileinput-caption");return e.mainTemplate.replace("{class}",e.mainClass).replace("{preview}",i).replace("{caption}",n).replace("{upload}",e.renderUpload()).replace("{remove}",e.renderRemove()).replace("{browse}",e.renderBrowse())},renderBrowse:function(){var e=this,i=e.browseClass+" btn-file",t="";return e.isDisabled&&(t=" disabled "),'<div class="'+i+'"'+t+"> "+e.browseIcon+e.browseLabel+" </div>"},renderRemove:function(){var e=this,i=e.removeClass+" btn-danger fileinput-remove fileinput-remove-button",t="";return e.showRemove?(e.isDisabled&&(t=" disabled "),'<button type="button" class="'+i+'"'+t+">"+e.removeIcon+e.removeLabel+"</button>"):""},renderUpload:function(){var e=this,i=e.uploadClass+" kv-fileinput-upload",t="",n="";return e.showUpload?(e.isDisabled&&(n=" disabled "),t=p(e.uploadUrl)?'<button type="submit" class="'+i+'"'+n+">"+e.uploadIcon+e.uploadLabel+"</button>":'<a href="'+e.uploadUrl+'" class="'+e.uploadClass+'"'+n+">"+e.uploadIcon+e.uploadLabel+"</a>"):""}},e.fn.fileinput=function(i){return this.each(function(){var t=e(this),n=t.data("fileinput");n||t.data("fileinput",n=new g(this,i)),"string"==typeof i&&n[i]()})},e.fn.fileinput=function(i){var t=Array.apply(null,arguments);return t.shift(),this.each(function(){var n=e(this),a=n.data("fileinput"),r="object"==typeof i&&i;a||n.data("fileinput",a=new g(this,e.extend({},e.fn.fileinput.defaults,r,e(this).data()))),"string"==typeof i&&a[i].apply(a,t)})},e.fn.fileinput.defaults={showCaption:!0,showPreview:!0,showRemove:!0,showUpload:!0,captionClass:"",previewClass:"",mainClass:"",mainTemplate:null,initialDelimiter:"*$$*",initialPreview:"",initialCaption:"",initialPreviewCount:0,initialPreviewContent:"",overwriteInitial:!0,previewTemplate:n,previewGenericTemplate:l,previewImageTemplate:l,previewTextTemplate:o,previewOtherTemplate:s,captionTemplate:a,browseLabel:"Browse &hellip;",browseIcon:'<i class="fa fa-folder-open"></i> &nbsp;',browseClass:"btn btn-warning",removeLabel:"Remove",removeIcon:'<i class="fa fa-ban-circle"></i> ',removeClass:"btn btn-default",uploadLabel:"Upload",uploadIcon:'<i class="fa fa-upload"></i> ',uploadClass:"btn btn-default",uploadUrl:null,maxFileSize:0,maxFileCount:0,msgSizeTooLarge:'File "{name}" (<b>{size} KB</b>) exceeds maximum allowed upload size of <b>{maxSize} KB</b>. Please retry your upload!',msgFilesTooMany:"Number of files selected for upload <b>({n})</b> exceeds maximum allowed limit of <b>{m}</b>. Please retry your upload!",msgFileNotFound:'File "{name}" not found!',msgFileNotReadable:'File "{name}" is not readable.',msgFilePreviewAborted:'File preview aborted for "{name}".',msgFilePreviewError:'An error occurred while reading the file "{name}".',msgValidationError:'<span class="text-danger"><i class="fa fa-exclamation-sign"></i> File Upload Error</span>',msgErrorClass:"file-error-message",msgLoading:"Loading  file {index} of {files} &hellip;",msgProgress:"Loading file {index} of {files} - {name} - {percent}% completed.",msgSelected:"{n} files selected",previewFileType:"image",wrapTextLength:250,wrapIndicator:' <span class="wrap-indicator" title="{title}" onclick="{dialog}">[&hellip;]</span>',elCaptionContainer:null,elCaptionText:null,elPreviewContainer:null,elPreviewImage:null,elPreviewStatus:null},e(document).ready(function(){var i=e("input.file[type=file]"),t=null!=i.attr("type")?i.length:0;t>0&&i.fileinput()})}(window.jQuery);
}

 function check_errors(a){
    if($('#'+a+' .error').length == 0){
        $('#'+a).find('input[type="submit"]').removeClass('disabled');
    }else{
        $('#'+a).find('input[type="submit"]').addClass('disabled');
    }
}

$(document).ready(function() {
    $('.top-menu-scroll').perfectScrollbar();
    $('#fixed').click(function(e) {
        e.preventDefault();
        if($('#sidebar-left').hasClass('minified')) {
            bootbox.alert('Unable to fix minified sidebar');
        } else {
            if($(this).hasClass('fixed')) {
                $.cookie('the_fixed', 'no');
            } else {
                $.cookie('the_fixed', 'yes');
            }
            cssStyle();
        }
    });


    $(document).ready(function(){
  
  //write function to display time
      function displayTime() {
        
        //define a variable for Date() object to store date/time information   
        var time = new Date();
        
        //define variables to store hours, minutes, and seconds
        //use Date object methods, i.e., getHours, getMinutes, getSeconds, to store desired info  
        var hours = time.getHours();
        var minutes = time.getMinutes();
        var seconds = time.getSeconds();
        
        //for 12hour clock, define a variable meridiem and default to ante meridiem (AM) 
        var meridiem = " AM";
        
        //since this is a 12 hour clock, once hours increase past 11, i.e., 12 -23, subtract 12 and set the meridiem
        //variable to post meridiem (PM) 
        if (hours>11){
          // hours = hours - 12;
          meridiem = " PM";
        }
        
        //at 12PM, the above if statement is set to subtract 12, making the hours read 0. 
        //create a statement that sets the hours back to 12 whenever it's 0.
        if (hours === 0){
          hours = 12;
        }
        
        //keep hours, seconds, and minutes at two digits all the time by adding a 0.
        if (hours<10){
          hours = "0" + hours;
        }
     
        if (minutes<10){
          minutes = "0" + minutes;
        }
        
        if (seconds<10){
          seconds = "0" + seconds;
        }
        
        //jquery to change text of clockDiv html element
        $(".clockDiv1").text(hours +":"+ minutes +":"+ seconds );
        
        //could also write this with vanilla JS as follows
        //var clock = document.getElesmentById('clockDiv');
       //clock.innerText = hours +":"+ minutes +":"+ seconds + meridiem;
        
      }
      //run displayTime function
      displayTime();
      //set interval to 1000ms (1s), so that info updates every second
      setInterval(displayTime, 1000);
    });
});

function text_center(x){
    if(x == null){
        x = '';
    }
    return '<div style="text-align: center;">'+x+'</div>'
}

function widthFunctions(e) {
    var l = $("#sidebar-left").outerHeight(true),
    c = $("#content").height(),
    co = $("#content").outerHeight(),
    h = $("header").height(),
    f = $("footer").height(),
    wh = $(window).height(),
    ww = $(window).width();
    if (ww < 992) {
        $("#main-menu-act").removeClass("minified").addClass("full").find("i").removeClass("fa-angle-double-right").addClass("fa-angle-double-left");
        $("body").removeClass("sidebar-minified");
        $("#content").removeClass("sidebar-minified");
        $("#sidebar-left").removeClass("minified")
        if ($.cookie('the_fixed') == 'yes') {
            $.cookie('the_fixed', 'no');
            $('#content, #sidebar-left, #header').removeAttr("style");
            $("#sidebar-left").css("overflow-y","visible");
            $('#fixedText').text('Fixed');
            $('#main-menu-act').addClass('full visible-md visible-lg').show();
            $('#fixed').removeClass('fixed');
            $('#sidebar-left').perfectScrollbar('destroy');
        }
    }
    if (ww < 998 && ww > 750) {
        $('#main-menu-act').hide();
        $("body").addClass("sidebar-minified");
        $("#content").addClass("sidebar-minified");
        $("#sidebar-left").addClass("minified");
        $(".dropmenu > .chevron").removeClass("opened").addClass("closed");
        $(".dropmenu").parent().find("ul").hide();
        $("#sidebar-left > div > ul > li > a > .chevron").removeClass("closed").addClass("opened");
        $("#sidebar-left > div > ul > li > a").addClass("open");
        $('#fixed').hide();
    }
    if (ww > 1024 && $.cookie('the_sidebar') != 'minified') {
        $('#main-menu-act').removeClass("minified").addClass("full").find("i").removeClass("fa-angle-double-right").addClass("fa-angle-double-left");
        $("body").removeClass("sidebar-minified");
        $("#content").removeClass("sidebar-minified");
        $("#sidebar-left").removeClass("minified");
        $("#sidebar-left > div > ul > li > a > .chevron").removeClass("opened").addClass("closed");
        $("#sidebar-left > div > ul > li > a").removeClass("open");
        $('#fixed').show();
    }
    if ($.cookie('the_fixed') == 'yes') {
        $('#content').css('margin-left', $('#sidebar-left').outerWidth(true)).css('margin-top', '40px');
        $('#sidebar-left').css('position', 'fixed').css('top', '40px').css('bottom', '40px').css('height', $(window).height()- 80);
    }
    if (ww > 767) {
        wh - 80 > l && $("#sidebar-left").css("min-height", wh - h - f - 30);
        wh - 80 > c && $("#content").css("min-height", wh - h - f - 30);
    } else {
        $("#sidebar-left").css("min-height", "0px");
    }
    //$(window).scrollTop($(window).scrollTop() + 1);
}

jQuery(document).ready(function(e) {
    window.location.hash ? e('#myTab a[href="' + window.location.hash + '"]').tab('show') : e("#myTab a:first").tab("show");
    e("#myTab2 a:first, #dbTab a:first").tab("show");
    e("#myTab a, #myTab2 a, #dbTab a").click(function(t) {
        t.preventDefault();
        e(this).tab("show");
    });
    e('[rel="popover"],[data-rel="popover"],[data-toggle="popover"]').popover();
    e("#toggle-fullscreen").button().click(function() {
        var t = e(this),
        n = document.documentElement;
        if (!t.hasClass("active")) {
            e("#thumbnails").addClass("modal-fullscreen");
            n.webkitRequestFullScreen ? n.webkitRequestFullScreen(window.Element.ALLOW_KEYBOARD_INPUT) : n.mozRequestFullScreen && n.mozRequestFullScreen()
        } else {
            e("#thumbnails").removeClass("modal-fullscreen");
            (document.webkitCancelFullScreen || document.mozCancelFullScreen || e.noop).apply(document)
        }
    });
    e(".btn-close").click(function(t) {
        t.preventDefault();
        e(this).parent().parent().parent().fadeOut()
    });
    e(".btn-minimize").click(function(t) {
        t.preventDefault();
        var n = e(this).parent().parent().next(".box-content");
        n.is(":visible") ? e("i", e(this)).removeClass("fa-chevron-up").addClass("fa-chevron-down") : e("i", e(this)).removeClass("fa-chevron-down").addClass("fa-chevron-up");
        n.slideToggle("slow", function() {
            widthFunctions();
        })
    });
});

jQuery(document).ready(function(e) {
    e("#main-menu-act").click(function() {
        if (e(this).hasClass("full")) {
            $.cookie('the_sidebar', 'minified');
            e(this).removeClass("full").addClass("minified").find("i").removeClass("fa-angle-double-left").addClass("fa-angle-double-right");
            e("body").addClass("sidebar-minified");
            e("#content").addClass("sidebar-minified");
            e("#sidebar-left").addClass("minified");
            e(".dropmenu > .chevron").removeClass("opened").addClass("closed");
            e(".dropmenu").parent().find("ul").hide();
            e("#sidebar-left > div > ul > li > a > .chevron").removeClass("closed").addClass("opened");
            e("#sidebar-left > div > ul > li > a").addClass("open");
            $('#fixed').hide();
        } else {
            $.cookie('the_sidebar', 'full');
            e(this).removeClass("minified").addClass("full").find("i").removeClass("fa-angle-double-right").addClass("fa-angle-double-left");
            e("body").removeClass("sidebar-minified");
            e("#content").removeClass("sidebar-minified");
            e("#sidebar-left").removeClass("minified");
            e("#sidebar-left > div > ul > li > a > .chevron").removeClass("opened").addClass("closed");
            e("#sidebar-left > div > ul > li > a").removeClass("open");
            $('#fixed').show();
        }
        return false;
    });
e(".dropmenu").click(function(t) {
    t.preventDefault();
    if (e("#sidebar-left").hasClass("minified")) {
        if (!e(this).hasClass("open")) {
            e(this).parent().find("ul").first().slideToggle();
            e(this).find(".chevron").hasClass("closed") ? e(this).find(".chevron").removeClass("closed").addClass("opened") : e(this).find(".chevron").removeClass("opened").addClass("closed")
        }
    } else {
        e(this).parent().find("ul").first().slideToggle();
        e(this).find(".chevron").hasClass("closed") ? e(this).find(".chevron").removeClass("closed").addClass("opened") : e(this).find(".chevron").removeClass("opened").addClass("closed")
    }
});
if (e("#sidebar-left").hasClass("minified")) {
    e("#sidebar-left > div > ul > li > a > .chevron").removeClass("closed").addClass("opened");
    e("#sidebar-left > div > ul > li > a").addClass("open");
    e("body").addClass("sidebar-minified")
}
});

$(document).ready(function() {
    cssStyle();
    // minimumResultsForSearch: 6,
    $('select, .select').select2({allowClear: true,});
    $('#customer, #rcustomer').select2({
       minimumInputLength: 1,
       ajax: {
        url: site.base_url+"customers/suggestions",
        dataType: 'json',
        quietMillis: 15,
        data: function (term, page) {
            return {
                term: term,
                limit: 10
            };
        },
        results: function (data, page) {
            if(data.results != null) {
                return { results: data.results };
            } else {
                return { results: [{id: '', text: 'No Match Found'}]};
            }
        }
    }
});
    $('#supplier, #rsupplier, .rsupplier').select2({
       minimumInputLength: 1,
       ajax: {
        url: site.base_url+"suppliers/suggestions",
        dataType: 'json',
        quietMillis: 15,
        data: function (term, page) {
            return {
                term: term,
                limit: 10
            };
        },
        results: function (data, page) {
            if(data.results != null) {
                return { results: data.results };
            } else {
                return { results: [{id: '', text: 'No Match Found'}]};
            }
        }
    }
});
    $('.input-tip').tooltip({placement: 'top', html: true, trigger: 'hover focus', container: 'body',
        title: function() {
            return $(this).attr('data-tip');
        }
    });
    $('.input-pop').popover({placement: 'top', html: true, trigger: 'hover', container: 'body',
        content: function() {
            return $(this).attr('data-tip');
        },
        title: function() {
            return '<b>' + $('label[for="' + $(this).attr('id') + '"]').text() + '</b>';
        }
    });
});

$(document).on('click', '*[data-toggle="lightbox"]', function(event) {
    event.preventDefault();
    $(this).ekkoLightbox();
});
$(document).on('click', '*[data-toggle="popover"]', function(event) {
    event.preventDefault();
    $(this).popover();
});

$(document).ajaxStart(function(){
  $('#ajaxCall').show();
}).ajaxStop(function(){
  $('#ajaxCall').hide();
});

$(document).ready(function() {
    $('input[type="checkbox"],[type="radio"]').not('.skip').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' // optional
    });
    $('textarea').not('.skip').redactor({
        buttons: ['formatting', '|', 'alignleft', 'aligncenter', 'alignright', 'justify', '|', 'bold', 'italic', 'underline', '|', 'unorderedlist', 'orderedlist', '|', /*'image', 'video',*/ 'link', '|', 'html'],
        formattingTags: ['p', 'pre', 'h3', 'h4'],
        minHeight: 100,
        changeCallback: function(e) {
            var editor = this.$editor.next('textarea');
            if($(editor).attr('required')){
                $('form[data-toggle="validator"]').bootstrapValidator('revalidateField', $(editor).attr('name'));
            }
        }
    });
    $(document).on('click', '.file-caption', function(){
        $(this).next('.input-group-btn').children('.btn-file').children('input.file').trigger('click');
    });
});

function suppliers(ele) {
    $(ele).select2({
       minimumInputLength: 1,
       ajax: {
        url: site.base_url+"suppliers/suggestions",
        dataType: 'json',
        quietMillis: 15,
        data: function (term, page) {
            return {
                term: term,
                limit: 10
            };
        },
        results: function (data, page) {
            if(data.results != null) {
                return { results: data.results };
            } else {
                return { results: [{id: '', text: 'No Match Found'}]};
            }
        }
    }
});
}

$(function() {
    $('.datetime').datetimepicker({format: site.dateFormats.js_ldate, fontAwesome: true, language: 'sma', weekStart: 1, todayBtn: 1, autoclose: 1, todayHighlight: 1, startView: 2, forceParse: 0});
    $('.date').datetimepicker({format: site.dateFormats.js_sdate, fontAwesome: true, language: 'sma', todayBtn: 1, autoclose: 1, minView: 2 });
    $(document).on('focus','.date', function(t) {
        $(this).datetimepicker({format: site.dateFormats.js_sdate, fontAwesome: true, todayBtn: 1, autoclose: 1, minView: 2 });
    });
    $(document).on('focus','.datetime', function() {
        $(this).datetimepicker({format: site.dateFormats.js_ldate, fontAwesome: true, weekStart: 1, todayBtn: 1, autoclose: 1, todayHighlight: 1, startView: 2, forceParse: 0});
    });
});

$(document).ready(function() {
    $('#dbTab a').on('shown.bs.tab', function(e) {
      var newt = $(e.target).attr('href');
      var oldt = $(e.relatedTarget).attr('href');
      $(oldt).hide();
      //$(newt).hide().fadeIn('slow');
      $(newt).hide().slideDown('slow');
  });
    $('.dropdown').on('show.bs.dropdown', function(e){
        $(this).find('.dropdown-menu').first().stop(true, true).slideDown('fast');
    });
    $('.dropdown').on('hide.bs.dropdown', function(e){
        $(this).find('.dropdown-menu').first().stop(true, true).slideUp('fast');
    });
    $('.hideComment').click(function(){
        $.ajax({ url: site.base_url+'welcome/hideNotification/'+$(this).attr('id')});
    });
    $('.tip').tooltip();
    $('body').on('click', '#delete', function(e) {
        e.preventDefault();
        $('#form_action').val($(this).attr('data-action'));
        $('#action-form').submit();
    });
    $('body').on('click', '#sync_quantity', function(e) {
        e.preventDefault();
        $('#form_action').val($(this).attr('data-action'));
        $('#action-form-submit').trigger('click');
    });
    $('body').on('click', '#excel', function(e) {
        e.preventDefault();
        $('#form_action').val($(this).attr('data-action'));
        $('#action-form-submit').trigger('click');
    });
    $('body').on('click', '#pdf', function(e) {
        e.preventDefault();
        $('#form_action').val($(this).attr('data-action'));
        $('#action-form-submit').trigger('click');
    });
    $('body').on('click', '#labelProducts', function(e) {
        e.preventDefault();
        $('#form_action').val($(this).attr('data-action'));
        $('#action-form-submit').trigger('click');
    });
    $('body').on('click', '#barcodeProducts', function(e) {
        e.preventDefault();
        $('#form_action').val($(this).attr('data-action'));
        $('#action-form-submit').trigger('click');
    });
});

$(document).ready(function() {
    $('#product-search').click(function() {
        $('#product-search-form').submit();
    });
    //feedbackIcons:{valid: 'fa fa-check',invalid: 'fa fa-times',validating: 'fa fa-refresh'},
    $('form[data-toggle="validator"]').bootstrapValidator({ message: 'Please enter/select a value', submitButtons: 'input[type="submit"]' });
    fields = $('.form-control');
    $.each(fields, function() {
        var id = $(this).attr('id');
        var iname = $(this).attr('name');
        var iid = '#'+id;
        if (!!$(this).attr('data-bv-notempty') || !!$(this).attr('required')) {
            $("label[for='" + id + "']").append(' *');
            $(document).on('change', iid, function(){
                $('form[data-toggle="validator"]').bootstrapValidator('revalidateField', iname);
            });
        }
    });
    $('body').on('click', 'label', function (e) {
        var field_id = $(this).attr('for');
        if (field_id) {
            if($("#"+field_id).hasClass('select')) {
                $("#"+field_id).select2("open");
                return false;
            }
        }
    });
    $('body').on('focus', 'select', function (e) {
        var field_id = $(this).attr('id');
        if (field_id) {
            if($("#"+field_id).hasClass('select')) {
                $("#"+field_id).select2("open");
                return false;
            }
        }
    });
    $('#myModal').on('hidden.bs.modal', function() {
        $(this).find('.modal-dialog').empty();
        //$(this).find('#myModalLabel').empty().html('&nbsp;');
        //$(this).find('.modal-body').empty().text('Loading...');
        //$(this).find('.modal-footer').empty().html('&nbsp;');
        $(this).removeData('bs.modal');
    });
    $('#myModal2').on('hidden.bs.modal', function () {
        $(this).find('.modal-dialog').empty();
        //$(this).find('#myModalLabel').empty().html('&nbsp;');
        //$(this).find('.modal-body').empty().text('Loading...');
        //$(this).find('.modal-footer').empty().html('&nbsp;');
        $(this).removeData('bs.modal');
        $('#myModal').css('zIndex', '1050');
        $('#myModal').css('overflow-y', 'scroll');
    });
    $('#myModal2').on('show.bs.modal', function () {
        $('#myModal').css('zIndex', '1040');
    });
      $('#myModalNoteProduct').on('hidden.bs.modal', function () {
        $(this).find('.modal-dialog').empty();
        //$(this).find('#myModalLabel').empty().html('&nbsp;');
        //$(this).find('.modal-body').empty().text('Loading...');
        //$(this).find('.modal-footer').empty().html('&nbsp;');
        $(this).removeData('bs.modal');
        $('#myModal').css('zIndex', '1050');
        $('#myModal').css('overflow-y', 'scroll');
        $('#myModal2').css('zIndex', '1050');
        $('#myModal2').css('overflow-y', 'scroll');
    });
    $('#myModalNoteProduct').on('show.bs.modal', function () {
        $('#myModal').css('zIndex', '1040');
        $('#myModal2').css('zIndex', '1040');
    });

    $('.modal').on('show.bs.modal', function () {
        $('#modal-loading').show();
        $('.blackbg').css('zIndex', '1041');
        $('.loader').css('zIndex', '1042');
    }).on('hide.bs.modal', function () {
        $('#modal-loading').hide();
        $('.blackbg').css('zIndex', '3');
        $('.loader').css('zIndex', '4');
    });
    $(document).on('click', '.po', function(e) {
        e.preventDefault();
        $('.po').popover({html: true, placement: 'left', trigger: 'manual'}).popover('show').not(this).popover('hide');
        return false;
    });
    $(document).on('click', '.po-close', function() {
        $('.po').popover('hide');
        return false;
    });
    $(document).on('click', '.po-delete', function(e) {
        var row = $(this).closest('tr');
        e.preventDefault();
        $('.po').popover('hide');
        var link = $(this).attr('href');
        $.ajax({type: "get", url: link,
            success: function(data) { row.remove(); addAlert(data, 'success'); },
            error: function(data) { addAlert('Failed', 'danger'); }
        });
        return false;
    });
    $(document).on('click', '.po-delete1', function(e) {
        e.preventDefault();
        $('.po').popover('hide');
        var link = $(this).attr('href');
        var s = $(this).attr('id'); var sp = s.split('__')
        $.ajax({type: "get", url: link,
            success: function(data) { addAlert(data, 'success'); $('#'+sp[1]).remove(); },
            error: function(data) { addAlert('Failed', 'danger'); }
        });
        return false;
    });
    $('body').on('click', '.bpo', function(e) {
        e.preventDefault();
        $(this).popover({html: true, trigger: 'manual'}).popover('toggle');
        return false;
    });
    $('body').on('click', '.bpo-close', function(e) {
        $('.bpo').popover('hide');
        return false;
    });
    $('#genNo').click(function(){
        var no = generateCardNo();
        $(this).parent().parent('.input-group').children('input').val(no);
        return false;
    });
    $('#inlineCalc').calculator({layout: ['_%+-CABS','_7_8_9_/','_4_5_6_*','_1_2_3_-','_0_._=_+'], showFormula:true});
    $('.calc').click(function(e) { e.stopPropagation();});
});

function addAlert(message, type) {
    $('#alerts').empty().append(
        '<div class="alert alert-' + type + '">' +
        '<button type="button" class="close" data-dismiss="alert">' +
        '&times;</button>' + message + '</div>');
}

$(document).ready(function() {
    if ($.cookie('the_sidebar') == 'minified') {
        $('#main-menu-act').removeClass("full").addClass("minified").find("i").removeClass("fa-angle-double-left").addClass("fa-angle-double-right");
        $("body").addClass("sidebar-minified");
        $("#content").addClass("sidebar-minified");
        $("#sidebar-left").addClass("minified");
        $(".dropmenu > .chevron").removeClass("opened").addClass("closed");
        $(".dropmenu").parent().find("ul").hide();
        $("#sidebar-left > div > ul > li > a > .chevron").removeClass("closed").addClass("opened");
        $("#sidebar-left > div > ul > li > a").addClass("open");
        $('#fixed').hide();
    } else {

        $('#main-menu-act').removeClass("minified").addClass("full").find("i").removeClass("fa-angle-double-right").addClass("fa-angle-double-left");
        $("body").removeClass("sidebar-minified");
        $("#content").removeClass("sidebar-minified");
        $("#sidebar-left").removeClass("minified");
        $("#sidebar-left > div > ul > li > a > .chevron").removeClass("opened").addClass("closed");
        $("#sidebar-left > div > ul > li > a").removeClass("open");
        $('#fixed').show();
    }
});

$(document).ready(function() {
    $('#daterange').daterangepicker({
        timePicker: true,
        format: (site.dateFormats.js_sdate).toUpperCase()+' HH:mm',
        ranges: {
         'Today': [moment().hours(0).minutes(0).seconds(0), moment()],
         'Yesterday': [moment().subtract('days', 1).hours(0).minutes(0).seconds(0), moment().subtract('days', 1).hours(23).minutes(59).seconds(59)],
         'Last 7 Days': [moment().subtract('days', 6).hours(0).minutes(0).seconds(0), moment().hours(23).minutes(59).seconds(59)],
         'Last 30 Days': [moment().subtract('days', 29).hours(0).minutes(0).seconds(0), moment().hours(23).minutes(59).seconds(59)],
         'This Month': [moment().startOf('month').hours(0).minutes(0).seconds(0), moment().endOf('month').hours(23).minutes(59).seconds(59)],
         'Last Month': [moment().subtract('month', 1).startOf('month').hours(0).minutes(0).seconds(0), moment().subtract('month', 1).endOf('month').hours(23).minutes(59).seconds(59)]
     }
 },
 function(start, end) {
    refreshPage(start.format('YYYY-MM-DD HH:mm'), end.format('YYYY-MM-DD HH:mm'));
});
});

function refreshPage(start, end) {
    window.location.replace(CURI + '/' + encodeURIComponent(start) + '/' + encodeURIComponent(end));
}

function retina() {
    retinaMode = window.devicePixelRatio > 1;
    return retinaMode
}

$(document).ready(function() {
    $('#cssLight').click(function(e) {
        e.preventDefault();
        $.cookie('the_style', 'light');
        cssStyle();
        return true;
    });
    $('#cssBlue').click(function(e) {
        e.preventDefault();
        $.cookie('the_style', 'blue');
        cssStyle();
        return true;
    });
    $('#cssBlack').click(function(e) {
        e.preventDefault();
        $.cookie('the_style', 'black');
        cssStyle();
        return true;
    });
    $("#toTop").click(function(e) {
        e.preventDefault();
        $("html, body").animate({scrollTop: 0}, 100);
    });
});
/*
 $(window).scroll(function() {
    if ($(this).scrollTop()) {
        $('#toTop').fadeIn();
    } else {
        $('#toTop').fadeOut();
    }
 });
*/
$(document).on('ifChecked', '.checkth, .checkft', function(event) {
    $('.checkth, .checkft').iCheck('check');
    $('.multi-select').each(function() {
        $(this).iCheck('check');
    });
});
$(document).on('ifUnchecked', '.checkth, .checkft', function(event) {
    $('.checkth, .checkft').iCheck('uncheck');
    $('.multi-select').each(function() {
        $(this).iCheck('uncheck');
    });
});
$(document).on('ifUnchecked', '.multi-select', function(event) {
    $('.checkth, .checkft').attr('checked', false);
    $('.checkth, .checkft').iCheck('update');
});

function fld(oObj) {
    if (oObj != null) {
        var aDate = oObj.split('-');
        var bDate = aDate[2].split(' ');
        year = aDate[0], month = aDate[1], day = bDate[0], time = bDate[1];
        if (site.dateFormats.js_sdate == 'dd-mm-yyyy')
            return day + "-" + month + "-" + year + " " + time;
        else if (site.dateFormats.js_sdate === 'dd/mm/yyyy')
            return day + "/" + month + "/" + year + " " + time;
        else if (site.dateFormats.js_sdate == 'dd.mm.yyyy')
            return day + "." + month + "." + year + " " + time;
        else if (site.dateFormats.js_sdate == 'mm/dd/yyyy')
            return month + "/" + day + "/" + year + " " + time;
        else if (site.dateFormats.js_sdate == 'mm-dd-yyyy')
            return month + "-" + day + "-" + year + " " + time;
        else if (site.dateFormats.js_sdate == 'mm.dd.yyyy')
            return month + "." + day + "." + year + " " + time;
        else
            return oObj;
    } else {
        return '';
    }
}

function fsd(oObj) {
    if (oObj != null) {
        var aDate = oObj.split('-');
        if (site.dateFormats.js_sdate == 'dd-mm-yyyy')
            return aDate[2] + "-" + aDate[1] + "-" + aDate[0];
        else if (site.dateFormats.js_sdate === 'dd/mm/yyyy')
            return aDate[2] + "/" + aDate[1] + "/" + aDate[0];
        else if (site.dateFormats.js_sdate == 'dd.mm.yyyy')
            return aDate[2] + "." + aDate[1] + "." + aDate[0];
        else if (site.dateFormats.js_sdate == 'mm/dd/yyyy')
            return aDate[1] + "/" + aDate[2] + "/" + aDate[0];
        else if (site.dateFormats.js_sdate == 'mm-dd-yyyy')
            return aDate[1] + "-" + aDate[2] + "-" + aDate[0];
        else if (site.dateFormats.js_sdate == 'mm.dd.yyyy')
            return aDate[1] + "." + aDate[2] + "." + aDate[0];
        else
            return oObj;
    } else {
        return '';
    }
}




function hrsd(oObj) {
    if (oObj != null) {
        if (site.dateFormats.js_sdate == 'dd-mm-yyyy'){
            var aDate = oObj.split('-');
            return aDate[2] + "-" + aDate[1] + "-" + aDate[0];            
        }else if (site.dateFormats.js_sdate === 'dd/mm/yyyy'){
            var aDate = oObj.split('/');
            return aDate[2] + "-" + aDate[1] + "-" + aDate[0];
        }else if (site.dateFormats.js_sdate == 'dd.mm.yyyy'){
            var aDate = oObj.split('.');            
            return aDate[2] + "-" + aDate[1] + "-" + aDate[0];
        }else if (site.dateFormats.js_sdate == 'mm/dd/yyyy'){
            var aDate = oObj.split('/'); 
            return aDate[2] + "-" + aDate[0] + "-" + aDate[1];
        }else if (site.dateFormats.js_sdate == 'mm-dd-yyyy'){
            var aDate = oObj.split('-');             
            return aDate[2] + "-" + aDate[0] + "-" + aDate[1];
        }else if (site.dateFormats.js_sdate == 'mm.dd.yyyy'){
            var aDate = oObj.split('.'); 
            return aDate[2] + "." + aDate[0] + "." + aDate[1];
        }else{
            return oObj;
        }
    } else {
        return '';
    }
}


function generateCardNo(x) {
    if(!x) { x = 16; }
    chars = "1234567890";
    no = "";
    for (var i=0; i<x; i++) {
       var rnum = Math.floor(Math.random() * chars.length);
       no += chars.substring(rnum,rnum+1);
   }
   return no;
}
function roundNumber(num, nearest) {
    if(!nearest) { nearest = 0.05; }
    return Math.round((num / nearest) * nearest);
}
function getNumber(x) {
    return accounting.unformat(x);
}
function formatQuantity(x) { return formatNumber(x, site.settings.decimals); }
function formatNumber(x, d) {
    if(!d) { d = site.settings.decimals; }
    if(site.settings.sac == 1) {
        return formatSA(parseFloat(x).toFixed(site.settings.decimals));
    }
    return accounting.formatNumber(x, d, site.settings.thousands_sep == 0 ? ' ' : site.settings.thousands_sep, site.settings.decimals_sep);
}
function formatMoney(x, symbol) {
    if(!symbol) { symbol = ""; }
    if(site.settings.sac == 1) {
        return symbol+''+formatSA(parseFloat(x).toFixed(site.settings.decimals));
    }
    return accounting.formatMoney(x, symbol, site.settings.decimals, site.settings.thousands_sep == 0 ? ' ' : site.settings.thousands_sep, site.settings.decimals_sep, "%s%v");
}
function is_valid_discount(mixed_var) {
    return (is_numeric(mixed_var) || (/([0-9]%)/i.test(mixed_var))) ? true : false;
}
function is_numeric(mixed_var) {
    var whitespace =
    " \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000";
    return (typeof mixed_var === 'number' || (typeof mixed_var === 'string' && whitespace.indexOf(mixed_var.slice(-1)) === -
        1)) && mixed_var !== '' && !isNaN(mixed_var);
}
function is_float(mixed_var) {
  return +mixed_var === mixed_var && (!isFinite(mixed_var) || !! (mixed_var % 1));
}
function decimalFormat(x) {
    if (x != null) {
        return '<div class="text-center">'+formatNumber(x)+'</div>';
    } else {
        return '<div class="text-center">0</div>';
    }
}
function currencyFormat(x) {
    if (x != null) {
        return '<div class="text-right">'+formatMoney(x)+'</div>';
    } else {
        return '<div class="text-right">0</div>';
    }
}
function formatDecimal(x) {
    return parseFloat(parseFloat(x).toFixed(site.settings.decimals));
}
function pqFormat(x) {
    if (x != null) {
        var d = '', pqc = x.split("___");
        for (index = 0; index < pqc.length; ++index) {
            var pq = pqc[index];
            var v = pq.split("__");
            d += v[0]+' ('+formatNumber(v[1])+')<br>';
        }
        return d;
    } else {
        return '';
    }
}
function checkbox(x) {
    return '<center><input class="checkbox multi-select" type="checkbox" name="val[]" value="' + x + '" /></center>';
}
function img_hl(x) {
    return x == null ? '' : '<center><ul class="enlarge"><li><img src="'+site.base_url+'assets/uploads/thumbs/' + x + '" alt="' + x + '" style="width:60px; height:60px;" class="" /><span><a href="'+site.base_url+'assets/uploads/' + x + '" data-toggle="lightbox"><img src="'+site.base_url+'assets/uploads/' + x + '" alt="' + x + '" style="width:200px;" class="img-thumbnail" /></a></span></li></ul></center>';
    //return x == null ? '' : '<center><a href="'+site.base_url+'assets/uploads/' + x + '" data-toggle="lightbox"><img src="'+site.base_url+'assets/uploads/thumbs/' + x + '" alt="" style="width:30px; height:30px;" /></a></center>';
}
function user_status(x) {
    var y = x.split("__");
    return y[0] == 1 ?
    '<div style="text-align:center"><a href="'+site.base_url+'auth/deactivate/'+ y[1] +'" data-toggle="modal" data-target="#myModal"><span class="label label-success"><i class="fa fa-check"></i> '+lang['active']+'</span></a></div>' :
    '<div style="text-align:center"><a href="'+site.base_url+'auth/activate/'+ y[1] +'"><span class="label label-danger"><i class="fa fa-times"></i> '+lang['inactive']+'</span><a/></div>';
}
function row_status(x) {
    if(x == null) {
        return '';
    } else if(x == 'pending') {
        return '<div class="text-center"><span class="label label-warning">'+lang[x]+'</span></div>';
    } else if(x == 'completed' || x == 'paid' || x == 'sent' || x == 'received') {
        return '<div class="text-center"><span class="label label-success">'+lang[x]+'</span></div>';
    } else if(x == 'partial' || x == 'transferring' || x == 'ordered') {
        return '<div class="text-center"><span class="label label-info">'+lang[x]+'</span></div>';
    } else if(x == 'due') {
        return '<div class="text-center"><span class="label label-danger">'+lang[x]+'</span></div>';
    } else {
        return '<div class="text-center"><span class="label label-default">'+lang[x]+'</span></div>';
    }
}
function formatSA (x) {
    x=x.toString();
    var afterPoint = '';
    if(x.indexOf('.') > 0)
       afterPoint = x.substring(x.indexOf('.'),x.length);
    x = Math.floor(x);
    x=x.toString();
    var lastThree = x.substring(x.length-3);
    var otherNumbers = x.substring(0,x.length-3);
    if(otherNumbers != '')
        lastThree = ',' + lastThree;
    var res = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree + afterPoint;

    return res;
}

$(document).ready(function() {
    $('body').on('click', '.product_link td:not(:first-child, :nth-child(2), :last-child)', function() {
        $('#myModal').modal({remote: site.base_url + 'products/modal_view/' + $(this).parent('.product_link').attr('id')});
        $('#myModal').modal('show');
        //window.location.href = site.base_url + 'products/view/' + $(this).parent('.product_link').attr('id');
    });
    $('body').on('click', '.purchase_link td:not(:first-child :last-child)', function() {
        $('#myModal').modal({remote: site.base_url + 'purchases/modal_view/' + $(this).parent('.purchase_link').attr('id')});
        $('#myModal').modal('show');
        //window.location.href = site.base_url + 'purchases/view/' + $(this).parent('.purchase_link').attr('id');
    });
    $('body').on('click', '.transfer_link td:not(:first-child, :last-child)', function() {
        $('#myModal').modal({remote: site.base_url + 'transfers/view/' + $(this).parent('.transfer_link').attr('id')});
        $('#myModal').modal('show');
    });
    $('body').on('click', '.invoice_link td:not(:first-child :last-child)', function() {
        $('#myModal').modal({remote: site.base_url + 'sales/modal_view/' + $(this).parent('.invoice_link').attr('id')});
        $('#myModal').modal('show');
        //window.location.href = site.base_url + 'sales/view/' + $(this).parent('.invoice_link').attr('id');
    });
    $('body').on('click', '.receipt_link td:not(:first-child :last-child)', function() {
        $('#myModal').modal({ remote: site.base_url + 'pos/view/' + $(this).parent('.receipt_link').attr('id') + '/1' });
    });
    $('body').on('click', '.return_link td', function() {
        window.location.href = site.base_url + 'sales/view_return/' + $(this).parent('.return_link').attr('id');
    });
    $('body').on('click', '.quote_link td:not(:first-child :last-child)', function() {
        $('#myModal').modal({remote: site.base_url + 'quotes/modal_view/' + $(this).parent('.quote_link').attr('id')});
        $('#myModal').modal('show');
        //window.location.href = site.base_url + 'quotes/view/' + $(this).parent('.quote_link').attr('id');
    });
    $('body').on('click', '.delivery_link td:not(:first-child, :last-child)', function() {
        $('#myModal').modal({remote: site.base_url + 'sales/view_delivery/' + $(this).parent('.delivery_link').attr('id')});
        $('#myModal').modal('show');
    });
    $('body').on('click', '.customer_link td:not(:first-child)', function() {
        $('#myModal').modal({remote: site.base_url + 'customers/edit/' + $(this).parent('.customer_link').attr('id')});
        $('#myModal').modal('show');
    });
    $('body').on('click', '.supplier_link td:not(:first-child)', function() {
        $('#myModal').modal({remote: site.base_url + 'suppliers/edit/' + $(this).parent('.supplier_link').attr('id')});
        $('#myModal').modal('show');
    });
    $('#clearLS').click(function(event) {
        bootbox.confirm(lang.r_u_sure, function(result) {
        if(result == true) {
            localStorage.clear();
            location.reload();
        }
        });
        return false;
    });
});

function fixAddItemnTotals() {
    var ai = $("#sticker");
    var aiTop = (ai.position().top)+250;
    var bt = $("#bottom-total");
    $(window).scroll(function() {
        var windowpos = $(window).scrollTop();
        if (windowpos >= aiTop) {
            ai.addClass("stick").css('width', ai.parent('form').width()).css('zIndex', 50001);
            if ($.cookie('the_fixed') == 'yes') { ai.css('top', '40px'); } else { ai.css('top', 0); }
            $('#add_item').removeClass('input-lg');
            $('.addIcon').removeClass('fa-2x');
        } else {
            ai.removeClass("stick").css('width', bt.parent('form').width()).css('zIndex', 50001);
            if ($.cookie('the_fixed') == 'yes') { ai.css('top', 0); }
            $('#add_item').addClass('input-lg');
            $('.addIcon').addClass('fa-2x');
        }
        if (windowpos <= ($(document).height() - $(window).height() - 120)) {
            bt.css('position', 'fixed').css('bottom', 0).css('width', bt.parent('form').width()).css('zIndex', 50000);
        } else {
            bt.css('position', 'static').css('width', ai.parent('form').width()).css('zIndex', 50000);
        }
    });
}
function ItemnTotals() {
    fixAddItemnTotals();
    $(window).bind("resize", fixAddItemnTotals);
}

if(site.settings.auto_detect_barcode == 1) {
    $(document).ready(function() {
        var pressed = false;
        var chars = [];
        $(window).keypress(function(e) {
            chars.push(String.fromCharCode(e.which));
            if (pressed == false) {
                setTimeout(function(){
                    if (chars.length >= 8) {
                        var barcode = chars.join("");
                        $( "#add_item" ).focus().autocomplete( "search", barcode );
                    }
                    chars = [];
                    pressed = false;
                },200);
            }
            pressed = true;
        });
    });
}

function validateNumber(evt) {
    var e = evt || window.event;
    var key = e.keyCode || e.which;

    if (!e.shiftKey && !e.altKey && !e.ctrlKey &&
    // numbers   
    key >= 48 && key <= 57 ||
    // Numeric keypad
    key >= 96 && key <= 105 ||
    // Backspace and Tab and Enter
    key == 8 || key == 9 || key == 13 ||
    // Home and End
    key == 35 || key == 36 ||
    // left and right arrows
    key == 37 || key == 39 ||
    // Del and Ins
    key == 46 || key == 45) {
        // input is VALID
    }
    else {
        // input is INVALID
        e.returnValue = false;
        if (e.preventDefault) e.preventDefault();
    }
}



$(window).bind("resize", widthFunctions);
$(window).load(widthFunctions);
