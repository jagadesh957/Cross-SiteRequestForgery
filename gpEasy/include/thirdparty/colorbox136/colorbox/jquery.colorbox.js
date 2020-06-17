(function($){var ag='colorbox',ah='hover',ai=true,aj=false,ak,al=!$.support.opacity,am=al&&!window.XMLHttpRequest,an='cbox_open',ao='cbox_load',ap='cbox_complete',aq='cbox_cleanup',ar='cbox_closed',as='resize.cbox_resize',at,au,av,aw,ax,ay,az,aA,aB,aC,aD,aE,aF,aG,aH,aI,aJ,aK,aL,aM,aN,aO,aP,aQ,aR,aS,aT,aU,aV,aW,aX={transition:"elastic",speed:350,width:aj,height:aj,innerWidth:aj,innerHeight:aj,initialWidth:"400",initialHeight:"400",maxWidth:aj,maxHeight:aj,minWidth:0,minHeight:0,scalePhotos:ai,scrolling:ai,inline:aj,html:aj,iframe:aj,photo:aj,href:aj,title:aj,rel:aj,opacity:0.9,preloading:ai,current:"image {current} of {total}",previous:"previous",next:"next",close:"close",aV:aj,overlayClose:ai,slideshow:aj,slideshowAuto:ai,slideshowSpeed:2500,slideshowStart:"start slideshow",slideshowStop:"stop slideshow",onOpen:aj,onLoad:aj,onComplete:aj,onCleanup:aj,onClosed:aj
};function setSize(aY,aZ){aZ=aZ==='x'?aC.width():aC.height();return(typeof aY==='string')?Math.round((aY.match(/%/)?(aZ/100)*parseInt(aY,10):parseInt(aY,10))):aY}
function isImage(ba){ba=$.isFunction(ba)?ba.call(aR):ba;return aU.photo||ba.match(/\.(gif|png|jpg|jpeg|bmp)(?:\?([^#]*))?(?:#(\.*))?$/i)}
function process(){for(var i in aU){if($.isFunction(aU[i])&&i.substring(0,2)!=='on'){aU[i]=aU[i].call(aR)}
}
aU.rel=aU.rel||aR.rel;aU.href=aU.href||aR.href;aU.title=aU.title||aR.title}
ak=$.fn.colorbox=function(bc,bd){var be=this;if(!be.length){if(be.selector===''){be=$('<a/>');bc.open=ai}else{return this}
}
be.each(function(){var bf=$.extend({},$(this).data(ag)?$(this).data(ag):aX,bc);$(this).data(ag,bf).addClass("cboxElement");if(bd){$(this).data(ag).onComplete=bd}
});if(bc&&bc.open){ak.launch(be)}
return this};ak.launch=function(bg){aR=bg;aU=$(aR).data(ag);process();if(aU.rel&&aU.rel!=='nofollow'){aB=$('.cboxElement').filter(function(){var bh=$(this).data(ag).rel||this.rel;return(bh===aU.rel)});aT=aB.index(aR);if(aT<0){aB=aB.add(aR);aT=aB.length-1}
}else{aB=$(aR);aT=0}
if(!aV){aV=ai;aW=ai;aS=aR;aS.blur();$(document).bind("keydown.cbox_close",function(e){if(e.keyCode===27){e.preventDefault();ak.close()}
}).bind("keydown.cbox_arrows",function(e){if(aB.length>1){if(e.keyCode===37){e.preventDefault();aL.click()}else if(e.keyCode===39){e.preventDefault();aK.click()}
}
});if(aU.overlayClose){at.css({"cursor":"pointer"}).one('click',ak.close)}
$.event.trigger(an);if(aU.onOpen){aU.onOpen.call(aR)}
at.css({"opacity":aU.opacity}).show();aU.w=setSize(aU.initialWidth,'x');aU.h=setSize(aU.initialHeight,'y');ak.position(0);if(am){aC.bind('resize.cboxie6 scroll.cboxie6',function(){at.css({width:aC.width(),height:aC.height(),top:aC.scrollTop(),left:aC.scrollLeft()})}).trigger("scroll.cboxie6")}
}
aI.add(aL).add(aK).add(aJ).add(aH).hide();aM.html(aU.close).show();ak.slideshow();ak.load()};ak.init=function(){function $div(id){return $('<div id="cbox'+id+'"/>')}
aC=$(window);au=$('<div id="colorbox"/>');at=$div("Overlay").hide();av=$div("Wrapper");$controls=$div("Controls").append(aI=$div("Current"),aJ=$div("Slideshow"),aK=$div("Next"),aL=$div("Previous"),aM=$div("Close"));aw=$div("Content").append(aD=$div("LoadedContent").css({width:0,height:0}),aF=$div("LoadingOverlay"),aG=$div("LoadingGraphic"),$controls,aH=$div("Title"));av.append($('<div/>').append($div("TopLeft"),ax=$div("TopCenter"),$div("TopRight")),$('<div/>').append(ay=$div("MiddleLeft"),aw,az=$div("MiddleRight")),$('<div/>').append($div("BottomLeft"),aA=$div("BottomCenter"),$div("BottomRight"))).children().children().css({'float':'left'});aE=$("<div style='position:absolute; top:0; left:0; width:9999px; height:0;'/>");$('body').prepend(at,au.append(av,aE));if(al){au.addClass('cboxIE');if(am){at.css('position','absolute')}
}
aw.children().bind('mouseover mouseout',function(){$(this).toggleClass(ah)}).addClass(ah);aN=ax.height()+aA.height()+aw.outerHeight(ai)-aw.height();aO=ay.width()+az.width()+aw.outerWidth(ai)-aw.width();aP=aD.outerHeight(ai);aQ=aD.outerWidth(ai);au.css({"padding-bottom":aN,"padding-right":aO}).hide();aK.click(ak.next);aL.click(ak.prev);aM.click(ak.close);aw.children().removeClass(ah);$('.cboxElement').live('click.colorbox',function(e){if(e.button!==0&&typeof e.button!=='undefined'){return ai}else{ak.launch(this);return aj}
})};ak.position=function(bi,bj){var
bk,bl=aC.height(),bm=Math.max(bl-aU.h-aP-aN,0)/2+aC.scrollTop(),bn=Math.max(document.documentElement.clientWidth-aU.w-aQ-aO,0)/2+aC.scrollLeft();bk=(au.width()===aU.w+aQ&&au.height()===aU.h+aP)?0:bi;av[0].style.width=av[0].style.height="9999px";function modalDimensions(bo){ax[0].style.width=aA[0].style.width=aw[0].style.width=bo.style.width;aG[0].style.height=aF[0].style.height=aw[0].style.height=ay[0].style.height=az[0].style.height=bo.style.height}
aH.width(aU.w);au.dequeue().animate({width:aU.w+aQ,height:aU.h+aP+aH.height(),top:bm,left:bn},{duration:bk,complete:function(){modalDimensions(this);aW=aj;av[0].style.width=(aU.w+aQ+aO)+"px";av[0].style.height=(aU.h+aP+aN+aH.height())+"px";if(bj){bj()}
},step:function(){modalDimensions(this)}
})};ak.resize=function(bp){if(!aV){return}
var bq,br,bs,bt,bu,bv,bw,bx=aU.transition==="none"?0:aU.speed;aC.unbind(as);if(!bp){bw=setTimeout(function(){var by=aD.wrapInner("<div style='overflow:auto'></div>").children();aU.h=by.height();aD.css({height:aU.h});by.replaceWith(by.children());ak.position(bx)},1);return}
aD.remove();aD=$('<div id="cboxLoadedContent"/>').html(bp);function getWidth(){aU.w=aU.w||aD.width();if(aU.minWidth){var bz=setSize(aU.minWidth,'x');if(aU.w<bz){aU.w=bz}
}
if(aU.mw&&aU.mw<aU.w){aU.w=aU.mw}
return aU.w}
function getHeight(){aU.h=aU.h||aD.height();if(aU.minHeight){var bA=setSize(aU.minHeight,'y');if(aU.h<bA){aU.h=bA}
}
if(aU.mh&&aU.mh<aU.h){aU.h=aU.mh}
return aU.h}
aD.hide().appendTo(aE).css({width:getWidth(),overflow:aU.scrolling?'auto':'hidden'}).css({height:getHeight()}).prependTo(aw);$('#cboxPhoto').css({cssFloat:'none'});if(am){$('select:not(#colorbox select)').filter(function(){return this.style.visibility!=='hidden'}).css({'visibility':'hidden'}).one(aq,function(){this.style.visibility='inherit'})}
function setPosition(s){aH.html(aU.title||aR.title||'');ak.position(s,function(){if(!aV){return}
if(al){if(bv){aD.fadeIn(100)}
au[0].style.removeAttribute("filter")}
if(aU.iframe){aD.append("<iframe id='cboxIframe'"+(aU.scrolling?" ":"scrolling='no'")+" name='iframe_"+new Date().getTime()+"' frameborder=0 src='"+aU.href+"' "+(al?"allowtransparency='true'":'')+" />")}
$controls.show();aD.show();aH.show();if(aB.length>1){aI.html(aU.current.replace(/\{current\}/,aT+1).replace(/\{total\}/,aB.length)).show();aK.html(aU.next).show();aL.html(aU.previous).show();if(aU.slideshow){aJ.show()}
}
aF.hide();aG.hide();$.event.trigger(ap);if(aU.onComplete){aU.onComplete.call(aR)}
if(aU.transition==='fade'){au.fadeTo(bx,1,function(){if(al){au[0].style.removeAttribute("filter")}
})}
aC.bind(as,function(){ak.position(0)})})}
if((aU.transition==='fade'&&au.fadeTo(bx,0,function(){setPosition(0)}))||setPosition(bx)){}
if(aU.preloading&&aB.length>1){br=aT>0?aB[aT-1]:aB[aB.length-1];bt=aT<aB.length-1?aB[aT+1]:aB[0];bu=$(bt).data(ag).href||bt.href;bs=$(br).data(ag).href||br.href;if(isImage(bu)){$('<img />').attr('src',bu)}
if(isImage(bs)){$('<img />').attr('src',bs)}
}
};ak.load=function(){var bB,bC,bD,bE=ak.resize;aW=ai;aR=aB[aT];aU=$(aR).data(ag);process();$.event.trigger(ao);if(aU.onLoad){aU.onLoad.call(aR)}
aU.h=aU.height?setSize(aU.height,'y')-aP-aN:aU.innerHeight?setSize(aU.innerHeight,'y'):aj;aU.w=aU.width?setSize(aU.width,'x')-aQ-aO:aU.innerWidth?setSize(aU.innerWidth,'x'):aj;aU.mw=aU.w;aU.mh=aU.h;if(aU.maxWidth){aU.mw=setSize(aU.maxWidth,'x')-aQ-aO;aU.mw=aU.w&&aU.w<aU.mw?aU.w:aU.mw}
if(aU.maxHeight){aU.mh=setSize(aU.maxHeight,'y')-aP-aN;aU.mh=aU.h&&aU.h<aU.mh?aU.h:aU.mh}
bB=aU.href;$controls.hide();aH.hide();aF.show();aG.show();if(aU.inline){$('<div id="cboxInlineTemp" />').hide().insertBefore($(bB)[0]).bind(ao+' '+aq,function(){$(this).replaceWith(aD.children())});bE($(bB))}else if(aU.iframe){bE(" ")}else if(aU.html){bE(aU.html)}else if(isImage(bB)){bC=new Image();bC.onload=function(){var bF;bC.onload=null;bC.id='cboxPhoto';$(bC).css({margin:'auto',border:'none',display:'block',cssFloat:'left'});if(aU.scalePhotos){bD=function(){bC.height-=bC.height*bF;bC.width-=bC.width*bF};if(aU.mw&&bC.width>aU.mw){bF=(bC.width-aU.mw)/bC.width;bD()}
if(aU.mh&&bC.height>aU.mh){bF=(bC.height-aU.mh)/bC.height;bD()}
}
bE(bC);if(aU.h){bC.style.marginTop=Math.max(aU.h-bC.height,0)/2+'px'}
if(aB.length>1){$('#cboxLoadedContent').live('click.cboxnext',ak.next)}else{$('#cboxLoadedContent').die('click.cboxnext')}
if(al){bC.style.msInterpolationMode='bicubic'}
};bC.src=bB}else{$('<div />').appendTo(aE).load(bB,function(bG,bH){if(bH==="success"){bE(this)}else{bE($("<p>Request unsuccessful.</p>"))}
})}
};ak.next=function(){if(!aW){aT=aT<aB.length-1?aT+1:0;ak.load()}
};ak.prev=function(){if(!aW){aT=aT>0?aT-1:aB.length-1;ak.load()}
};ak.slideshow=function(){var bI,bJ,bK='cboxSlideshow_';aJ.bind(ar,function(){aJ.unbind();clearTimeout(bJ);au.removeClass(bK+"off"+" "+bK+"on")});function start(){aJ
.text(aU.slideshowStop).bind(ap,function(){bJ=setTimeout(ak.next,aU.slideshowSpeed)}).bind(ao,function(){clearTimeout(bJ)}).one("click",function(){bI();$(this).removeClass(ah)});au.removeClass(bK+"off").addClass(bK+"on")}
bI=function(){clearTimeout(bJ);aJ
.text(aU.slideshowStart).unbind(ap+' '+ao).one("click",function(){start();bJ=setTimeout(ak.next,aU.slideshowSpeed);$(this).removeClass(ah)});au.removeClass(bK+"on").addClass(bK+"off")};if(aU.slideshow&&aB.length>1){if(aU.slideshowAuto){start()}else{bI()}
}
};ak.close=function(){$.event.trigger(aq);if(aU.onCleanup){aU.onCleanup.call(aR)}
aV=aj;$(document).unbind("keydown.cbox_close keydown.cbox_arrows");aC.unbind(as+' resize.cboxie6 scroll.cboxie6');at.css({cursor:'auto'}).fadeOut('fast');au
.stop(ai,aj).fadeOut('fast',function(){$('#colorbox iframe').attr('src','about:blank');aD.remove();au.css({'opacity':1});try{aS.focus()}catch(er){}
$.event.trigger(ar);if(aU.onClosed){aU.onClosed.call(aR)}
})};ak.element=function(){return $(aR)};ak.settings=aX;$(ak.init)}(jQuery));