Fx.Move=new Class({Extends:Fx.Morph,options:{relativeTo:document.body,position:"center",edge:false,offset:{x:0,y:0}},start:function(destination){var element=this.element,topLeft=element.getStyles("top","left");if(topLeft.top=="auto"||topLeft.left=="auto"){element.setPosition(element.getPosition(element.getOffsetParent()))}return this.parent(element.position(Object.merge({},this.options,destination,{returnPos:true})))}});Element.Properties.move={set:function(options){this.get("move").cancel().setOptions(options);return this},get:function(){var move=this.retrieve("move");if(!move){move=new Fx.Move(this,{link:"cancel"});this.store("move",move)}return move}};Element.implement({move:function(options){this.get("move").start(options);return this}});