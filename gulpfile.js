const gulp = require("gulp");
const sass = require("gulp-sass")(require("sass"));
const postcss = require("gulp-postcss");
const cssnano = require("cssnano");
const autoprefixer = require("autoprefixer");
const concat = require("gulp-concat");
const rename = require("gulp-rename");
const uglify = require("gulp-uglify");

gulp.task("styles", () => {
  const plugins = [autoprefixer(), cssnano()];

  return (
    gulp
      .src(["src/scss/styles.scss"])
      // Process SCSS to CSS
      .pipe(sass().on("error", sass.logError))

      .pipe(concat("styles.css"))

      // Autoprefix and minify
      .pipe(postcss(plugins))

      .pipe(rename({ suffix: ".min" }))

      // Save minified file
      .pipe(gulp.dest("assets/css/"))
  );
});

gulp.task("admin-styles", () => {
  const plugins = [autoprefixer(), cssnano()];

  return (
    gulp
      .src(["src/scss/admin.scss"])
      // Process SCSS to CSS
      .pipe(sass().on("error", sass.logError))

      .pipe(concat("admin.css"))

      // Autoprefix and minify
      .pipe(postcss(plugins))

      .pipe(rename({ suffix: ".min" }))

      // Save minified file
      .pipe(gulp.dest("assets/css/"))
  );
});

gulp.task("admin-scripts", () => {
  return (
    gulp
      .src(["src/js/admin/components/*.js", "src/js/admin/main.js"])

      .pipe(concat("admin.js"))

      // Minify js
      .pipe(uglify())

      .pipe(rename({ suffix: ".min" }))

      // Save minified file
      .pipe(gulp.dest("assets/js/"))
  );
});

gulp.task("watch", () => {
  // Run tasks on SCSS changes
  gulp.watch(["src/scss/**/*.scss", "src/js/**/*.js"], (done) => {
    gulp.series(["styles", "admin-styles", "admin-scripts"])(done);
  });
});

gulp.task("default", gulp.series(["styles", "admin-styles", "admin-scripts"]));
