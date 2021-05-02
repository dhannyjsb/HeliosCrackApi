// Edit at line 126 => 137 with:
// Replace https://api.evoniamc.eu/api/lib/skins/skins.php with your skin api

function updateSelectedAccount(authUser){
    let username = 'Aucun compte sélectionné'
    if(authUser != null){
        if(authUser.displayName != null){
            username = authUser.displayName
        }
        if(authUser.uuid != null){
            document.getElementById('avatarContainer').style.backgroundImage = `url('https://api.evoniamc.eu/api/lib/skins/skins.php?user=${authUser.username}&mode=head')`
        }
    }
    user_text.innerHTML = username
}