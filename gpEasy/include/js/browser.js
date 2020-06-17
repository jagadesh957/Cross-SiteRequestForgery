$(function(){$('.browser_list .list_item').dblclick(function(){OpenFile(this)});$('a.select').click(function(e){OpenFile(this);e.preventDefault();return false});if(window.top.opener&&(typeof(window.top.opener.CKEDITOR)!='undefined')){$('a.ck_select').show()}
function OpenFile(a){var f=$(a).children('input[name=fileUrl]').val();if(!f){return}
if(!window.top.opener){return}
if(typeof(window.top.opener.CKEDITOR)!='undefined'){var g=getQueryValue('CKEditorFuncNum',window.top.location.search);window.top.opener.CKEDITOR.tools.callFunction(g,f)}else{window.top.opener.SetUrl(f)}
window.top.close();window.top.opener.focus()}
function getQueryValue(h,i){var r=new RegExp('(?:[\?&]|&amp;)'+h+'=([^&]+)','i');var v=i.match(r);return(v&&v.length>1)?v[1]:''}
});