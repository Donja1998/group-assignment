const url = 'https://tasty.p.rapidapi.com/recipes/auto-complete?prefix=chicken%20soup';
const options = {
	method: 'GET',
	headers: {
		'X-RapidAPI-Key': '54d479fdbbmshfb5b5617c7c3e5bp1127e8jsn7cabcc632e47',
		'X-RapidAPI-Host': 'tasty.p.rapidapi.com'
	}
};

try {
	const response = await fetch(url, options);
	const result = await response.text();
	console.log(result);
} catch (error) {
	console.error(error);
}