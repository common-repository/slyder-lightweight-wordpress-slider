<?php function add_slYder_styles() { ?>
<style type="text/css">
.homeHeroTitleBG {
  background: none repeat scroll 0 0 rgba(0, 0, 0, 0.6);
  display: block;
  float: right;
  left: 0;
  overflow: hidden;
  padding: 10px 15px 15px;
  position: relative;
  text-align: left;
  top: -340px;
  width: 70%;
  /* Fallback for web browsers that doesn't support RGBa */
	background: rgb(0, 0, 0);
	/* RGBa with 0.6 opacity */
	background: rgba(0, 0, 0, 0.6);
	/* For IE 5.5 - 7*/
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);
	/* For IE 8*/
	-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";
}
.homeThumbs {
  position: relative;
  top: -250px;
  height: 250px;
}
.homeThumbClick {
  cursor: pointer;
}
.homeThumbs img{
  border: 1px solid #fff;
  width: 80px;
  height: 50px;
  margin: 0 5px 5px 0;
}
.heroHome .active img {
	border-color: #999;
  position: relative;
  left: 2px;
}
.homeHeroTitle{
  font-size: 25px;
  line-height: 28px;
  color: #dfdfdf !important;
}
.homeHeroTitle:hover {
	color: #fff !important;
}
.largeBGHome{
  width: 84%;
  height: 240px;
  float: right;
  border: 1px solid #999;
}
.heroHome{
  background: #ddd;
  padding: 5px;
  color: #fff; overflow: hidden; height: 243px;
}
.heroHome .active {

}
</style>

<?php } ?>
