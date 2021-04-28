//Code by Superkooka!#0612

const ConfigManager     = require('./configmanager')
const { v3: uuidv3 }      = require("uuid");
const { machineIdSync } = require("node-machine-id");

exports.addAccount = async function(username, password){
    let hash = require("crypto").createHash("sha512");

    //modify with your url of api
    let base = 'https://www.utopicube.fr/api/auth/';
    
    hash.update(password);
    password = hash.digest("hex");

    let mode = null;
    let uuid = null;
    let skin = null;

    if (username.startsWith('@')) {
      let url = base + `?email=${username}&password=${password}`
    } else {
      let url = base + `?pseudo=${username}&password=${password}`
    }
   await fetch(url)
    .then((response) => response.json())
    .then((response) => {
        if ("ok" != response.status) {
          throw new Error(
            "User or password not correspond, please retry"
          );
        }

        mode = response.mode;
        if ("lien" == mode) {
          skin = response.lien
        } else if ("uuid" == mode) {
          uuid = response.uuid
        }
    });

    const ret = ConfigManager.addAuthAccount(
      "uuid" == mode ? uuid : uuidv3(username + machineIdSync(), uuidv3.DNS),
      "DevMetricsCrackedHelios",
      username,
      username,
      "link" == mode ? skin : null
    );

    if (ConfigManager.getClientToken() == null) {
      ConfigManager.setClientToken("DevMetricsCrackedHelios");
    }
    ConfigManager.save();
    return ret;
}

exports.removeAccount = async function(uuid){
    try {
        ConfigManager.removeAuthAccount(uuid)
        ConfigManager.save()
        return Promise.resolve()
    } catch (err){
        return Promise.reject(err)
    }
}

exports.validateSelected = async function(){
    return true
}
