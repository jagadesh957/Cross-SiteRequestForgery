if(!window.CKEDITOR)window.CKEDITOR=(function(){var a={timestamp:'',version:'3.2',revision:'5205',_:{},status:'unloaded',basePath:(function(){var d=window.CKEDITOR_BASEPATH||'';if(!d){var e=document.getElementsByTagName('script');for(var f=0;f<e.length;f++){var g=e[f].src.match(/(^|.*[\\\/])ckeditor(?:_basic)?(?:_source)?.js(?:\?.*)?$/i);if(g){d=g[1];break}}}if(d.indexOf('://')==-1)if(d.indexOf('/')===0)d=location.href.match(/^.*?:\/\/[^\/]*/)[0]+d;else d=location.href.match(/^[^\?]*\/(?:)/)[0]+d;return d})(),getUrl:function(d){if(d.indexOf('://')==-1&&d.indexOf('/')!==0)d=this.basePath+d;if(this.timestamp&&d.charAt(d.length-1)!='/')d+=(d.indexOf('?')>=0?'&':'?')+('t=')+this.timestamp;return d}},b=window.CKEDITOR_GETURL;if(b){var c=a.getUrl;a.getUrl=function(d){return b.call(a,d)||c.call(a,d)}}return a})();if(CKEDITOR.loader)CKEDITOR.loader.load('core/ckeditor');else{CKEDITOR._autoLoad='core/ckeditor';document.write('<script type="text/javascript" src="'+CKEDITOR.getUrl('_source/core/loader.js')+'"></script>')}