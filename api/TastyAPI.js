const fetchRecipes = async (from, size, tags) => {
  const response = await fetch(`https://tasty.p.rapidapi.com/recipes/list?from=${from}&size=${size}&tags=${tags}`, {
    method: 'GET',
    headers: {
      'x-rapidapi-key': '283446c48emshbf3494700d6f6b8p1a602fjsnd7203fbfd1',
      'x-rapidapi-host': 'tasty.p.rapidapi.com'
    }
  });
  const data = await response.json();
  return data.results;
}

// Example usage: retrieve the first 10 recipes tagged as "under_30_minutes"
const recipes = await fetchRecipes(0, 10, 'under_30_minutes');
console.log(recipes);