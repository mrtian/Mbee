(function(){
	var _modules={},
	scripts = document.getElementsByTagName('script'),
	len = scripts.length,
	currentScript = scripts[len-1];

	function define(id,fn,update){
		if(id==="")
			return;
		if(_modules[id]&&!update)
			throw"Module "+id+" already exist!";
		if(typeof fn === "function"){
			_modules[id] = {id:id,fn:fn,exports:{}}
		}else{
			_modules[id]={exports:fn}}
	}
	function require(id){
		var mod=_modules[id],fn,ret;
		if(mod){
			fn=mod.fn;
			if(fn){
				ret=typeof fn==="function"?fn(require,mod.exports,mod):fn;
				if(ret)
					mod.exports=ret;
				delete mod.fn
			}
			return mod.exports
		}else{
			throw"Module "+id+" not exist!"
		}
	} 
	window.mbee = {};
	mbee.module =function(){
		if(arguments.length>=2){
			return define.apply(define,arguments)
		}else{
			return require.apply(require,arguments)
		}
	}

	//load init
	if(currentScript){
		var init = currentScript.getAttribute('init'),
			_blankFn = function(){};
		if(init && init !=''){
			init = init.split(',');

			if(init.length){
				var complete = function(){
					for(var i=0;i<init.length;i++){
						mt.module(init[i]);
					}
				}
				currentScript.onload = complete;
				//script.onerror = onerror;

				currentScript.onreadystatechange = function () {
					var state = this.readyState;
					if (state === 'loaded' || state === 'complete') {
						script.onreadystatechange = _blankFn;
						complete();
					}
				}
			}
		}
	}	
})()