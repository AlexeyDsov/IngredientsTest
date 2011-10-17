/**
 * Iframe Ajax Tool
 * @todo refactoring
**/
var IAT = {
    submit: function(form, options)
    {
        IAT.targedForm(form, IAT.makeIframe(options));

        if (options && typeof(options.onBefore) == 'function') {
            return options.onBefore();
        } else {
            return true;
        }
    },

    makeIframe: function(options)
    {
        var iframeName = 'f' + Math.floor(10000 * Math.random());

        var divNode = document.createElement('DIV');
        divNode.innerHTML = '<iframe class="iframer" style="display:none" src="about:blank" id="' + iframeName + '" name="' + iframeName + '" onload="IAT.loaded(\'' + iframeName + '\')"></iframe>';
        document.body.appendChild(divNode);

        var iframeNode = document.getElementById(iframeName);

        if (options && typeof(options.onComplete) == 'function') {
            iframeNode.onComplete = options.onComplete;
        }

        return iframeName;
    },

    targedForm: function(form, name)
    {
        form.setAttribute('target', name);
    },

    loaded: function(id)
    {
        var iframeNode = document.getElementById(id);

        if (iframeNode.contentDocument) {
            var doc = iframeNode.contentDocument;
        } else if (iframeNode.contentWindow) {
            var doc = iframeNode.contentWindow.document;
        } else {
            var doc = window.frames[id].document;
        }

        if (doc.location.href == "about:blank") {
            return;
        }

        if (typeof(iframeNode.onComplete) == 'function') {
            iframeNode.onComplete(doc.body.innerHTML);
        }
    }
}