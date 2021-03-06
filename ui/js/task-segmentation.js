<script type="text/javascript">
    // Globals...

    var parameters; // Instance of Parameters Class holding data retrieved from Server (e.g. Translations)

    // Passed from PHP
    var siteLocation;


    var MAX_SEGMENTS = 10;
    var CURR_SEGMENTS = 2;
    var TOTAL_WORD_COUNT = 0;


    $(document).ready(function() {
        siteLocation = getSetting("siteLocation");
        parameters = new Parameters(loadingComplete);

        var segmentationElements = document.getElementById('segmentationElements');
        var formSelect = document.createElement('select');
        formSelect.setAttribute('name', 'segmentationValue');
        formSelect.setAttribute('onchange', "segmentSelectChange(this);");
        TOTAL_WORD_COUNT = jQuery('#totalWordCount').val();
        var defaultWordCount = TOTAL_WORD_COUNT / CURR_SEGMENTS;
        defaultWordCount = parseInt(defaultWordCount);

        for(var i=0; i < MAX_SEGMENTS-1; ++i) {
            var optionNode = document.createElement('option');
            optionNode.setAttribute('value', i+2);
            optionNode.innerHTML += (i+2);
            formSelect.appendChild(optionNode);

            if (i < CURR_SEGMENTS) {
                jQuery('#wordCount_' + i).val(defaultWordCount);
            }
        }
        segmentationElements.appendChild(formSelect);
    })

    function getSetting(text) {
        return document.getElementById(text).innerHTML;
    }

    function loadingComplete() {
    }

    function segmentSelectChange(node) {
        var index = node.selectedIndex;
        var value = parseInt(node.options[index].value);
        var templateNode = document.getElementById('taskUploadTemplate_0');
        var taskSegments = document.getElementById('taskSegments');
        var defaultWordCount = Math.round(TOTAL_WORD_COUNT / value);

        if(value < CURR_SEGMENTS) {
            for(var i=CURR_SEGMENTS; i > 0; i--) {
                if (i > value) {
                    var del = document.getElementById('taskUploadTemplate_' + (i-1));
                    taskSegments.removeChild(del);
                } else {
                    jQuery('#wordCount_' + (i-1)).val(defaultWordCount);
                }
            }

        } else if(value > CURR_SEGMENTS) {
            for(var i=0 ; i < value; i++) {
                if (i >= CURR_SEGMENTS) {
                    var clonedNode = templateNode.cloneNode(true);
                    var inputs = clonedNode.getElementsByTagName('input');
                    clonedNode.setAttribute('id',clonedNode.getAttribute("id").replace("0",i));
                    for(var j=0; j < inputs.length; j++){
                        inputs.item(j).setAttribute('id', inputs.item(j).getAttribute('id').replace("0", i));
                        inputs.item(j).setAttribute('name', inputs.item(j).getAttribute('id'));
                    }
                    taskSegments.appendChild(clonedNode);
                    var label = jQuery("#taskUploadTemplate_" + i).find("#label_0");
                    if (label != null) {
                        label.text("File #" + (i + 1) +":");
                        label.attr("id", label.attr("id").replace("0", i));
                    }
                }

                jQuery('#wordCount_' + i).val(defaultWordCount);
            }
        }
        CURR_SEGMENTS = value;
    }

    // http://stackoverflow.com/questions/3717793/javascript-file-upload-size-validation
    function checkFileSizes() {
        var input, file, maxFileSizeBytes, totalFileSizes = 0;

        if (!window.FileReader) {
            return true;
        }

        for (var i=0; i < MAX_SEGMENTS; ++i) {
            input = document.getElementById("segmentationUpload_" + i);
            if (input && input.files) {
                if (!input.files[0]) {
                    window.alert(parameters.getTranslation("task_segmentation_15").replace("<strong>", "").replace("</strong>", ""));
                    return false;
                }
                else {
                    file = input.files[0];
                    totalFileSizes += file.size;
                }
            }
        }

        maxFileSizeBytes = parseInt(document.getElementById("maxfilesize").innerHTML);

        if (totalFileSizes > maxFileSizeBytes) {
            window.alert(parameters.getTranslation("common_maximum_file_total_size_vs_limit_is").replace("%2$s", (maxFileSizeBytes/1024/1024).toFixed(1)).replace("%1$s", (totalFileSizes/1024/1024).toFixed(1)));
            return false;
        }
        return true;
    }
</script>
