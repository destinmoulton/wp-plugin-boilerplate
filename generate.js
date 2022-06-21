// noinspection SpellCheckingInspection

/**
 * Generate a Plugin.
 *
 * Copy config.template.yaml to config.yaml.
 */

const yaml = require("yaml");
const fs = require("fs-extra");
const glob = require("glob");
const path = require("path");
const clone = require("git-clone");

const configPath = "config.yaml";
const bpslug = "plugin-boilerplate";
fs.stat(configPath, (err, stat) => {
  if (err != null) {
    console.error(
      "config.yaml does not exist. Rename config.template.yaml to config.yaml and configure the settings."
    );
    process.exit(-1);
  }
});

const file = fs.readFileSync(configPath, "utf8");
const options = yaml.parse(file);

// verify the destination directory exists
try {
  const ds = fs.statSync(options.DESTINATION);
  if (!ds.isDirectory()) {
    console.error(`DESTINATION: ${options.DESTINATION} is not a directory.`);
    process.exit(-1);
  }
} catch (err) {
  console.error(`DESTINATION: ${options.DESTINATION} is not a directory.`);
  process.exit(-1);
}

const glb = path.join(bpslug, "**/*");
glob(glb, {}, (err, fsitems) => {
  for (const item of fsitems) {
    const st = fs.statSync(item);
    if (st.isDirectory()) {
      let dir = lstripslug(item, bpslug);
      dir = path.join(options.DESTINATION, options.PLUGIN_SLUG, dir);
      if (undefined === fs.statSync(dir, { throwIfNoEntry: false })) {
        // Dir doesn't exist, so create it
        try {
          fs.mkdirSync(dir, { recursive: true });
          console.log(`created directory: ${dir}`);
        } catch (err) {
          console.error(`error: unable to create directory '${dir}'`);
        }
      }
    } else {
      let ftxt = "";
      try {
        /** @var string */
        ftxt = fs.readFileSync(item, "utf8");
        for (const oKey in options) {
          if (oKey !== "DESTINATION" || oKey !== "ZIP") {
            const oVal = options[oKey];
            ftxt = ftxt.replaceAll(oKey, oVal);
          }
        }
      } catch (err) {
        console.error(`error: failed to open file ${item}`);
      }

      let fpath = lstripslug(item, bpslug);
      fpath = path.join(options.DESTINATION, options.PLUGIN_SLUG, fpath);

      let parts = fpath.split(path.sep);
      let filename = parts.pop();
      if (filename === bpslug + ".php") {
        // Replace the plugin filename with the real slug
        fpath = path.join(parts.join(path.sep), options.PLUGIN_SLUG + ".php");
      }

      try {
        let fstat = fs.statSync(item);
        // Create a new file with the same mode
        fs.writeFileSync(fpath, ftxt, { mode: fstat.mode });
        console.log(`generated file: ${fpath}`);
      } catch (err) {
        console.error(`error: failed to generate file ${fpath}`);
      }
    }
  }
});

const cachepath = ".cache";
const libpath = path.join(options.DESTINATION, options.PLUGIN_SLUG, "lib");
const lkeys = Object.keys(options.LIBRARIES);
if (lkeys.length > 0) {
  for (const lkey in options.LIBRARIES) {
    const lrepo = options.LIBRARIES[lkey];
    const lpath = path.join(libpath, lkey);
    const lcache = path.join(cachepath, lkey);
    if (undefined === fs.statSync(lcache, { throwIfNoEntry: false })) {
      clone(lrepo, lcache, {}, () => {
        console.log(`cloned library: ${lkey} into .cache`);
        fs.copySync(lcache, lpath);
        console.log(`copied ${lkey} from cache into lib folder: ${lpath}`);
      });
    } else {
      // Copy the existing cache
      fs.copySync(lcache, lpath);
      console.log(`copied ${lkey} from cache into lib folder: ${lpath}`);
    }
  }
}

/**
 *
 * @param orig string
 * @param slug string
 */
function lstripslug(orig, slug) {
  return orig.replace(slug, "");
}

// TODO: Zip feature
