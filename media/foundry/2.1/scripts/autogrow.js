(function(){var e=function(e){var t=this,n=function(){e.fn.autogrow=function(t){return this.filter("textarea").each(function(){function o(){i.val(n.val()),i[0].scrollHeight;var e=i[0].scrollHeight;e>r.maxHeight&&r.maxHeight>0?(e=r.maxHeight,n.css("overflow","auto")):(e=e<r.minHeight?r.minHeight:e,n.css("overflow","hidden")),n.height(e+r.lineHeight*r.lineBleed)}var n=e(this);if(n.hasClass("shadow"))return;n.unbind("focus.autogrow blur.autogrow keyup.autogrow keypress.autogrow autogrow");var r=t||{},i=n.data("shadow"),s=n.outerHeight()-n.innerHeight();i&&i.remove(),i=n.clone().unbind().removeAttr("name").addClass("shadow").css({visibility:"hidden",height:0,minHeight:0,margin:0}),n.css("position")!=="absolute"&&i.css({position:"relative",borderTop:"none",borderBottom:"none",marginTop:0,marginBottom:-1*(parseInt(n.css("paddingTop"))+parseInt(n.css("paddingBottom")))}),i.insertAfter(n),n.data("shadow",i).bind("focus.autogrow blur.autogrow keyup.autogrow keypress.autogrow autogrow",o),r.lineHeight==undefined&&(r.lineHeight=i.val(" ")[0].scrollHeight),r.minHeight==undefined&&(r.minHeight=n.height()),r.maxHeight==undefined&&(r.maxHeight=0),r.lineBleed==undefined&&(r.lineBleed=0),o()}),this}};n(),t.resolveWith(n)};dispatch("autogrow").containing(e).to("Foundry/2.1 Modules")})();