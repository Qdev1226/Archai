/**
 * Simple Vanilla Javascript Drag and Scroll
 * https://codepen.io/thenutz/pen/VwYeYEE
 */
const portfolio_columns = document.querySelector('.portfolio-columns');
let isDown              = false;
let startX;
let scrollLeft;

portfolio_columns.addEventListener('mousedown', (e) => {
  isDown = true;
  portfolio_columns.classList.add('active');
  startX     = e.pageX - portfolio_columns.offsetLeft;
  scrollLeft = portfolio_columns.scrollLeft;
});
portfolio_columns.addEventListener('mouseleave', () => {
  isDown = false;
  portfolio_columns.classList.remove('active');
});
portfolio_columns.addEventListener('mouseup', () => {
  isDown = false;
  portfolio_columns.classList.remove('active');
});
portfolio_columns.addEventListener('mousemove', (e) => {
	if (!isDown) {
return;
	}
  e.preventDefault();
  const x                      = e.pageX - portfolio_columns.offsetLeft;
  const walk                   = (x - startX) * 3; //scroll-fast
  portfolio_columns.scrollLeft = scrollLeft - walk;
  console.log(walk);
});
