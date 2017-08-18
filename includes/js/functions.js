/**
 * Add suburl.
 */
function addURL(url)
{
    window.location.hash = url;
    return;
}

/**
 * Add subtitle.
 */
function addTitle(title)
{
    document.title += " | "+title;
    return;
}

/**
 * Add keywords.
 */
function addKeywords(keywords)
{
    var metaTags = document.getElementsByTagName("meta");
    for(var i=0; i<metaTags.length; i++){
        if (metaTags[i].name=="keywords"){
            metaTags[i].content += ", "+keywords;
        }
    }
    return;
}

/**
 * Change page description.
 */
function addDescription(description)
{
    var metaTags = document.getElementsByTagName("meta");
    for(var i=0; i<metaTags.length; i++){
        if (metaTags[i].name=="description"){
            metaTags[i].content = description;
        }
    }
    return;
}

/**
 * Redirect page.
 */
function redirect(url)
{
    window.location = SITEURL+url;
}
