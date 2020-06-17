var r=0;function LOG(a){$('<div />').html(r+' :: '+a).appendTo('body');r++}
function LOGO(s){var a='';for(var i in s){try{a+='<br/><b>'+i+"</b> = "+s[i]+"\n"}catch(m){a+='<br/><b>'+i+"</b> -- not allowed -- \n"}
}
u(a)}
gpadmin={uploadFile:function(el){el.parentNode.className='active';var t=el.value.toString();while(pos=t.search('\\\\')){if(pos==-1){break}
t=t.substr(pos+1)}
$('<div><a href="javascript:void(0)" onclick="gpadmin.rmFile(this)" >Remove</a><div class="name">'+t+'</div></div>').appendTo(el.parentNode);if($('#gp_upload_list .active').size()<5){gpadmin.addFile()}
},addFile:function(){$('#gp_upload_field').clone().attr('id','').attr('class','upload_ready').appendTo('#gp_upload_list')},rmFile:function(el){$(el).closest('.active').remove();if($('#gp_upload_list .upload_ready').size()<1){gpadmin.addFile()}
}
};function createCookie(v,x,y){if(y){var z=new Date();z.setTime(z.getTime()+(y*24*60*60*1000));var A="; expires="+z.toGMTString()}
else var A="";document.cookie=v+"="+x+A+"; path=/"}
function eraseCookie(B){createCookie(B,"",-1)}
function jPrep(a,b){b=b||'gpreq=json&jsoncallback=?';a+=(a.indexOf('?')==-1)?'?':'&';return a+b}
function ajaxResponse(C){for(i in C){var j=C[i];switch(j.DO){case 'replace':$(j.SELECTOR).replaceWith(j.CONTENT);break;case 'inner':$(j.SELECTOR).html(j.CONTENT);break;case 'eval':eval(j.CONTENT);break;default:if(typeof(gpresponse[j.DO])=='function'){gpresponse[j.DO].call(this,j)}else{alert('nothing for: '+j.DO)}
break}
}
try{$.fn.colorbox.close()}catch(er){}
$.fn.gpeasy.LiveJQ();$('#loading').clearQueue().fadeOut()}
$(function(){var D=1;var cl='';if(IE7){cl='IE7CLASS'}else{cl='STCLASS'}
$('body').addClass(cl);var a=$('#gpadminpanel');if(a.size()>0){$('body').prepend($('#gp_admin_html')).prepend('<div id="gpspace" />');a.show();$('div#gpspace').css('height',a.height());adminInit()}
eraseCookie('cookie_cmd');window.setTimeout(function(){cInit()},100);function adminInit(){if(!isadmin){return}
var E=$('<div id="editable_bar">&nbsp;</div>').appendTo('body');var F=0;$('a.ExtraEditLink').each(function(a,b){$b=$(b);var id=D++;$b.closest('.editable_area').attr('id','EditableArea_'+id).data('cid',id);var t=b.title.replace(/_/g,' ');if(t.length>15){t=t.substr(0,14)+'...'}
var c=$b.clone(true).data('cid',id).addClass('editable_mark').removeClass('ExtraEditLink').text(t).appendTo('body');var w=c.outerWidth();if(F<w){F=w}
c.css({left:0,right:0}).appendTo(E);$(b).hide()});$('#edit_list_new, #editable_bar').hover(function(){$('#editable_bar').stop(true).fadeTo('fast',1.0).animate({width:F})},function(){$('#editable_bar').stop(true).fadeTo('fast',1.0).animate({width:'7px'},1300)});function reposition(){var G=$('body').height();var H=$(window).height();if(G>H){H=G}
$('#editable_bar').css('height',H);var I=Array();$('.editable_mark').each(function(a,b){var id=$(b).data('cid');var c=$('#EditableArea_'+id);if(c.length==0){return}
var J=c.offset().top;J=Math.round(J/20)*20;while(I[J]){J+=20}
I[J]=true;b.style.top=J+'px'})}
reposition();window.setInterval(reposition,5000);$('.editable_area').bind('mousemove',function(e){$this=$(this);if($this.parent().closest('.editable_area').length>0){e.stopPropagation()}
AreaOverlay($this,1);$('.edit_area_overlay').stop(true,true).delay(1200).fadeOut(700)}).bind('mouseleave',function(){$('.edit_area_overlay').stop(true,true).hide()});$('.ExtraEditLink').bind('mouseenter',function(){$('.edit_area_overlay').stop(true,true).show()}).bind('mouseleave',function(){$('.edit_area_overlay').stop(true,true).hide()});$('.editable_mark').hover(function(){var id=$(this).data('cid');c=$('#EditableArea_'+id);if(c.length==0){return}
var a=$('#editable_bar');var K=(c.offset().left+c.width()-20);if(K<a.width()){$('#editable_bar').fadeTo('slow',.5)}
AreaOverlay(c)},function(){$('.edit_area_overlay').hide();$('#editable_bar').fadeTo('fast',1.0)});var L=false;function AreaOverlay(c,d){var M,ch,id,N,w,h,O,P,Q,R,S;$('.edit_area_overlay').stop(true).fadeTo(10,1.0);M=false;id=c.attr('id');h=c.outerHeight();ch=c.children();if(ch.length==2){ch=ch.eq(1);M=ch.offset();w=ch.outerWidth()}
if(M===false){M=c.offset();w=c.outerWidth()}
N=id+':'+M.top+':'+d;if(N==L){return}
L=N;O='';P=0;Q=$(document).width();if(d){O=c.children('.ExtraEditLink').clone(true).show();$('#edit_area_overlay_top').html(O);ht=O.outerHeight();lnklft=Math.round(O.outerWidth()/2)+2;S=M.left+w+10+Math.round(O.outerWidth()/2);P=Math.round(ht/2);if(S>Q){var T=S-Q;lnklft=Math.max(0,lnklft-T);if(lnklft<3){P-=5}
}
O.css({'position':'relative','display':'inline-block','top':Math.round(2-ht/2),'left':lnklft})}else{$('#edit_area_overlay_top').html('')}
var rh=Math.max(0,h-P+2);var rl=(M.left+w)-2;if(rl>=Q){rl=Q-2}
$('#edit_area_overlay_top').css({'top':(M.top-2),'left':(M.left)}).height(0).width(w).show();$('#edit_area_overlay_right').css({'top':(M.top+P),'left':rl}).height(rh).width(0).show();$('#edit_area_overlay_bottom').css({'top':(M.top+h),'left':(M.left)}).height(0).width(w).show();$('#edit_area_overlay_left').css({'top':(M.top-2),'left':(M.left-2)}).height(h+4).width(0).show()}
}
function cInit(){$().ajaxError(function(U,V,W,X){LOGO(V);alert('Error fetching response. Reload this page to continue.')});$('form').live('mousedown',function(e){$this=$(this);if($this.data('gpForms')=='checked'){return}
if(typeof(this['return'])!=='undefined'){this['return'].value=window.location}
$this.data('gpForms','checked')});$('input').live('click',function(e){$this=$(this);var a=$this.attr('class');var p=a.indexOf(' ');if(p>0){a=a.substr(0,p)}
switch(a){case 'gppost':case 'gpajax':return gpPublic.post(this);default:if(typeof(gpinputs[a])=='function'){return gpinputs[a].call(this)}
return true}
return false});$.fn.gpeasy.LiveJQ();$('a[name]').live('click',function(e){var a=$(this).attr('name');var b=$(this).attr('rel');switch(a){case 'remote':var Y=window.location.href;var Z=Y.indexOf('#');if(Z>0){Y=Y.substr(0,Z)}
var c=jPrep(this.href,'gpreq=body');c+='&in='+encodeURIComponent(Y);$.fn.colorbox({opacity:0.75,iframe:true,href:c,minWidth:"90%",height:"90%",maxWidth:"850",onLoad:function(){document.body.scroll='no';document.body.style.overflow='hidden'},onCleanup:function(){document.body.scroll='auto';document.body.style.overflow=''}
});break;case 'ajax_box':$.fn.colorbox({opacity:0.75,href:jPrep(this.href,'gpreq=flush'),open:true,maxWidth:"90%",minWidth:"300",minHeight:"300"});break;case 'inline_box':var c=$(b).find('form').get(0);if(c){$(this).find('input').each(function(i,j){if(c[j.name]){c[j.name].value=j.value}
})}
$.fn.colorbox({opacity:0.75,inline:true,href:b,open:true,maxWidth:"90%",minWidth:"300",minHeight:"300"});break;case 'gpajax':$.fn.gpeasy.jGoTo(this.href);break;case 'creq':$.fn.gpeasy.cGoTo(this.search);break;case 'close_message':this.parentNode.style.display='none';break;case 'gallery':$('a[rel='+b+']').colorbox({opacity:0.75,maxWidth:"90%",maxHeight:"90%",resize:true,minWidth:"300",minHeight:"300"});$.fn.colorbox.launch(this);break;default:if(typeof(gplinks[a])=='function'){return gplinks[a].call(this);break}
return true}
e.preventDefault();return false})}
function loading(){$('#loading').css({'zIndex':'9001000'}).fadeTo('slow',.4)}
var aa;aa=$.fn.gpeasy=function(){return this};aa.jGoTo=function(a){loading();a=jPrep(a);$.getJSON(a,ajaxResponse)};aa.cGoTo=function(a){createCookie('cookie_cmd',encodeURIComponent(a),1);var b=window.location.href;window.location=b};aa.post=function(a){loading();var ab=$(a).closest('form');var c=jPrep(ab.attr('action'));$.post(c,ab.serialize(),ajaxResponse,'json');return false};aa.LiveJQ=function(){$('.expand_child').unbind('mouseenter.live').unbind('mouseleave.live').bind('mouseenter.live',function(){$(this).addClass('expand');if($(this).hasClass('simple_top')){$(this).addClass('simple_top_hover')}
}).bind('mouseleave.live',function(){$(this).removeClass('expand').removeClass('simple_top_hover')})}});