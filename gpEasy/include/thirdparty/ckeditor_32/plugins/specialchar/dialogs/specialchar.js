CKEDITOR.dialog.add('specialchar',function(a){var b,c=a.lang.specialChar,d=function(j){var k,l;if(j.data)k=j.data.getTarget();else k=new CKEDITOR.dom.element(j);if(k.getName()=='a'&&(l=k.getChild(0).getHtml())){k.removeClass('cke_light_background');b.hide();a.insertHtml(l)}},e=CKEDITOR.tools.addFunction(d),f,g=function(j,k){var l;k=k||j.data.getTarget();if(k.getName()=='span')k=k.getParent();if(k.getName()=='a'&&(l=k.getChild(0).getHtml())){if(f)h(null,f);var m=b.getContentElement('info','htmlPreview').getElement();b.getContentElement('info','charPreview').getElement().setHtml(l);m.setHtml(CKEDITOR.tools.htmlEncode(l));k.getParent().addClass('cke_light_background');f=k}},h=function(j,k){k=k||j.data.getTarget();if(k.getName()=='span')k=k.getParent();if(k.getName()=='a'){b.getContentElement('info','charPreview').getElement().setHtml('&nbsp;');b.getContentElement('info','htmlPreview').getElement().setHtml('&nbsp;');k.getParent().removeClass('cke_light_background');f=undefined}},i=CKEDITOR.tools.addFunction(function(j){j=new CKEDITOR.dom.event(j);var k=j.getTarget(),l,m,n=j.getKeystroke();switch(n){case 38:if(l=k.getParent().getParent().getPrevious()){m=l.getChild([k.getParent().getIndex(),0]);m.focus();h(null,k);g(null,m)}j.preventDefault();break;case 40:if(l=k.getParent().getParent().getNext()){m=l.getChild([k.getParent().getIndex(),0]);if(m&&m.type==1){m.focus();h(null,k);g(null,m)}}j.preventDefault();break;case 32:d({data:j});j.preventDefault();break;case 39:case 9:if(l=k.getParent().getNext()){m=l.getChild(0);if(m.type==1){m.focus();h(null,k);g(null,m);j.preventDefault(true)}else h(null,k)}else if(l=k.getParent().getParent().getNext()){m=l.getChild([0,0]);if(m&&m.type==1){m.focus();h(null,k);g(null,m);j.preventDefault(true)}else h(null,k)}break;case 37:case CKEDITOR.SHIFT+9:if(l=k.getParent().getPrevious()){m=l.getChild(0);m.focus();h(null,k);g(null,m);j.preventDefault(true)}else if(l=k.getParent().getParent().getPrevious()){m=l.getLast().getChild(0);m.focus();h(null,k);g(null,m);j.preventDefault(true)}else h(null,k);break;default:return}});return{title:c.title,minWidth:430,minHeight:280,buttons:[CKEDITOR.dialog.cancelButton],charColumns:17,chars:['!','&quot;','#','$','%','&amp;',"'",'(',')','*','+','-','.','/','0','1','2','3','4','5','6','7','8','9',':',';','&lt;','=','&gt;','?','@','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','[',']','^','_','`','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','{','|','}','~','&euro;(EURO SIGN)','&lsquo;(LEFT SINGLE QUOTATION MARK)','&rsquo;(RIGHT SINGLE QUOTATION MARK)','&ldquo;(LEFT DOUBLE QUOTATION MARK)','&rdquo;(RIGHT DOUBLE QUOTATION MARK)','&ndash;(EN DASH)','&mdash;(EM DASH)','&iexcl;(INVERTED EXCLAMATION MARK)','&cent;(CENT SIGN)','&pound;(POUND SIGN)','&curren;(CURRENCY SIGN)','&yen;(YEN SIGN)','&brvbar;(BROKEN BAR)','&sect;(SECTION SIGN)','&uml;(DIAERESIS)','&copy;(COPYRIGHT SIGN)','&ordf;(FEMININE ORDINAL INDICATOR)','&laquo;(LEFT-POINTING DOUBLE ANGLE QUOTATION MARK)','&not;(NOT SIGN)','&reg;(REGISTERED SIGN)','&macr;(MACRON)','&deg;(DEGREE SIGN)','&plusmn;(PLUS-MINUS SIGN)','&sup2;(SUPERSCRIPT TWO)','&sup3;(SUPERSCRIPT THREE)','&acute;(ACUTE ACCENT)','&micro;(MICRO SIGN)','&para;(PILCROW SIGN)','&middot;(MIDDLE DOT)','&cedil;(CEDILLA)','&sup1;(SUPERSCRIPT ONE)','&ordm;(MASCULINE ORDINAL INDICATOR)','&raquo;(RIGHT-POINTING DOUBLE ANGLE QUOTATION MARK)','&frac14;(VULGAR FRACTION ONE QUARTER)','&frac12;(VULGAR FRACTION ONE HALF)','&frac34;(VULGAR FRACTION THREE QUARTERS)','&iquest;(INVERTED QUESTION MARK)','&Agrave;(LATIN CAPITAL LETTER A WITH GRAVE)','&Aacute;(LATIN CAPITAL LETTER A WITH ACUTE)','&Acirc;(LATIN CAPITAL LETTER A WITH CIRCUMFLEX)','&Atilde;(LATIN CAPITAL LETTER A WITH TILDE)','&Auml;(LATIN CAPITAL LETTER A WITH DIAERESIS)','&Aring;(LATIN CAPITAL LETTER A WITH RING ABOVE)','&AElig;(LATIN CAPITAL LETTER AE)','&Ccedil;(LATIN CAPITAL LETTER C WITH CEDILLA)','&Egrave;(LATIN CAPITAL LETTER E WITH GRAVE)','&Eacute;(LATIN CAPITAL LETTER E WITH ACUTE)','&Ecirc;(LATIN CAPITAL LETTER E WITH CIRCUMFLEX)','&Euml;(LATIN CAPITAL LETTER E WITH DIAERESIS)','&Igrave;(LATIN CAPITAL LETTER I WITH GRAVE)','&Iacute;(LATIN CAPITAL LETTER I WITH ACUTE)','&Icirc;(LATIN CAPITAL LETTER I WITH CIRCUMFLEX)','&Iuml;(LATIN CAPITAL LETTER I WITH DIAERESIS)','&ETH;(LATIN CAPITAL LETTER ETH)','&Ntilde;(LATIN CAPITAL LETTER N WITH TILDE)','&Ograve;(LATIN CAPITAL LETTER O WITH GRAVE)','&Oacute;(LATIN CAPITAL LETTER O WITH ACUTE)','&Ocirc;(LATIN CAPITAL LETTER O WITH CIRCUMFLEX)','&Otilde;(LATIN CAPITAL LETTER O WITH TILDE)','&Ouml;(LATIN CAPITAL LETTER O WITH DIAERESIS)','&times;(MULTIPLICATION SIGN)','&Oslash;(LATIN CAPITAL LETTER O WITH STROKE)','&Ugrave;(LATIN CAPITAL LETTER U WITH GRAVE)','&Uacute;(LATIN CAPITAL LETTER U WITH ACUTE)','&Ucirc;(LATIN CAPITAL LETTER U WITH CIRCUMFLEX)','&Uuml;(LATIN CAPITAL LETTER U WITH DIAERESIS)','&Yacute;(LATIN CAPITAL LETTER Y WITH ACUTE)','&THORN;(LATIN CAPITAL LETTER THORN)','&szlig;(LATIN SMALL LETTER SHARP S)','&agrave;(LATIN SMALL LETTER A WITH GRAVE)','&aacute;(LATIN SMALL LETTER A WITH ACUTE)','&acirc;(LATIN SMALL LETTER A WITH CIRCUMFLEX)','&atilde;(LATIN SMALL LETTER A WITH TILDE)','&auml;(LATIN SMALL LETTER A WITH DIAERESIS)','&aring;(LATIN SMALL LETTER A WITH RING ABOVE)','&aelig;(LATIN SMALL LETTER AE)','&ccedil;(LATIN SMALL LETTER C WITH CEDILLA)','&egrave;(LATIN SMALL LETTER E WITH GRAVE)','&eacute;(LATIN SMALL LETTER E WITH ACUTE)','&ecirc;(LATIN SMALL LETTER E WITH CIRCUMFLEX)','&euml;(LATIN SMALL LETTER E WITH DIAERESIS)','&igrave;(LATIN SMALL LETTER I WITH GRAVE)','&iacute;(LATIN SMALL LETTER I WITH ACUTE)','&icirc;(LATIN SMALL LETTER I WITH CIRCUMFLEX)','&iuml;(LATIN SMALL LETTER I WITH DIAERESIS)','&eth;(LATIN SMALL LETTER ETH)','&ntilde;(LATIN SMALL LETTER N WITH TILDE)','&ograve;(LATIN SMALL LETTER O WITH GRAVE)','&oacute;(LATIN SMALL LETTER O WITH ACUTE)','&ocirc;(LATIN SMALL LETTER O WITH CIRCUMFLEX)','&otilde;(LATIN SMALL LETTER O WITH TILDE)','&ouml;(LATIN SMALL LETTER O WITH DIAERESIS)','&divide;(DIVISION SIGN)','&oslash;(LATIN SMALL LETTER O WITH STROKE)','&ugrave;(LATIN SMALL LETTER U WITH GRAVE)','&uacute;(LATIN SMALL LETTER U WITH ACUTE)','&ucirc;(LATIN SMALL LETTER U WITH CIRCUMFLEX)','&uuml;(LATIN SMALL LETTER U WITH DIAERESIS)','&uuml;(LATIN SMALL LETTER U WITH DIAERESIS)','&yacute;(LATIN SMALL LETTER Y WITH ACUTE)','&thorn;(LATIN SMALL LETTER THORN)','&yuml;(LATIN SMALL LETTER Y WITH DIAERESIS)','&OElig;(LATIN CAPITAL LIGATURE OE)','&oelig;(LATIN SMALL LIGATURE OE)','&#372;(LATIN CAPITAL LETTER W WITH CIRCUMFLEX)','&#374(LATIN CAPITAL LETTER Y WITH CIRCUMFLEX)','&#373(LATIN SMALL LETTER W WITH CIRCUMFLEX)','&#375;(LATIN SMALL LETTER Y WITH CIRCUMFLEX)','&sbquo;(SINGLE LOW-9 QUOTATION MARK)','&#8219;(SINGLE HIGH-REVERSED-9 QUOTATION MARK)','&bdquo;(DOUBLE LOW-9 QUOTATION MARK)','&hellip;(HORIZONTAL ELLIPSIS)','&trade;(TRADE MARK SIGN)','&#9658;(BLACK RIGHT-POINTING POINTER)','&bull;(BULLET)','&rarr;(RIGHTWARDS ARROW)','&rArr;(RIGHTWARDS DOUBLE ARROW)','&hArr;(LEFT RIGHT DOUBLE ARROW)','&diams;(BLACK DIAMOND SUIT)','&asymp;(ALMOST EQUAL TO)'],onLoad:function(){var j=this.definition.charColumns,k=this.definition.chars,l=['<table role="listbox" aria-labelledby="specialchar_table_label" style="width: 320px; height: 100%; border-collapse: separate;" align="center" cellspacing="2" cellpadding="2" border="0">'],m=0,n=k.length,o,p;while(m<n){l.push('<tr>');for(var q=0;q<j;q++,m++){if(o=k[m]){p='';o=o.replace(/\((.*?)\)/,function(r,s){p=s;return ''});p=p||o;l.push('<td class="cke_dark_background" style="cursor: default"><a href="javascript: void(0);" role="option" aria-posinset="'+(m+1)+'"',' aria-setsize="'+n+'"',' aria-labelledby="cke_specialchar_label_'+m+'"',' style="cursor: inherit; display: block; height: 1.25em; margin-top: 0.25em; text-align: center;" title="',CKEDITOR.tools.htmlEncode(p),'" onkeydown="CKEDITOR.tools.callFunction( '+i+', event, this )"'+' onclick="CKEDITOR.tools.callFunction('+e+', this); return false;"'+' tabindex="-1">'+'<span style="margin: 0 auto;cursor: inherit">'+o+'</span>'+'<span class="cke_voice_label" id="cke_specialchar_label_'+m+'">'+p+'</span></a>')}else l.push('<td class="cke_dark_background">&nbsp;');l.push('</td>')}l.push('</tr>')}l.push('</tbody></table>','<span id="specialchar_table_label" class="cke_voice_label">'+a.lang.common.options+'</span>');this.getContentElement('info','charContainer').getElement().setHtml(l.join(''))},contents:[{id:'info',label:a.lang.common.generalTab,title:a.lang.common.generalTab,padding:0,align:'top',elements:[{type:'hbox',align:'top',widths:['320px','90px'],children:[{type:'html',id:'charContainer',html:'',onMouseover:g,onMouseout:h,focus:function(){var j=this.getElement().getElementsByTag('a').getItem(0);setTimeout(function(){j.focus();g(null,j)})},onShow:function(){var j=this.getElement().getChild([0,0,0,0,0]);setTimeout(function(){j.focus();g(null,j)})},onLoad:function(j){b=j.sender}},{type:'hbox',align:'top',widths:['100%'],children:[{type:'vbox',align:'top',children:[{type:'html',html:'<div></div>'},{type:'html',id:'charPreview',className:'cke_dark_background',style:"border:1px solid #eeeeee;font-size:28px;height:40px;width:70px;padding-top:9px;font-family:'Microsoft Sans Serif',Arial,Helvetica,Verdana;text-align:center;",html:'<div>&nbsp;</div>'},{type:'html',id:'htmlPreview',className:'cke_dark_background',style:"border:1px solid #eeeeee;font-size:14px;height:20px;width:70px;padding-top:2px;font-family:'Microsoft Sans Serif',Arial,Helvetica,Verdana;text-align:center;",html:'<div>&nbsp;</div>'}]}]}]}]}]}});