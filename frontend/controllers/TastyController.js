const { fetchRecipes } = require('../utils/tastyApi');

const getRecipes = async (req, res) => {
  const recipes = await fetchRecipes();
  res.json(recipes);
}

module.exports = {
  getRecipes
};
