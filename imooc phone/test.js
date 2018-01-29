var person1 = {
	fangfa1 : function(){
		return "aa";
	},

	fangfa2 : function(){
		return "bb";
	}
};

var person2 = {
	fangfa1 : function(){
		return "cc";
	},

	fangfa2 : function(){
		return "dd";
	}
};

var people = [person1,person2];
console.log(person1.fangfa1());//aa cc
