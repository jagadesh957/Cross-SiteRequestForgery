function getmenus(){var b='';$('a.menu_marker').each(function(c,d){b+='&menus['+d.nextSibling.id+']='+d.name});return b}
function menuclicks(){var a=getmenus();$.fn.gpeasy.jGoTo(this.href+a);return false}
function menupost(){var a=getmenus();var af=$(this).closest('form');af.attr('action',jPrep(af.attr('action'),a));$.fn.gpeasy.post(this);return false}
function replacemenu(j){var a=$(j.SELECTOR).find('ul:first').replaceWith(j.CONTENT)}
gplinks['withmenu']=menuclicks;gpinputs['menupost']=menupost;gpresponse['replacemenu']=replacemenu;