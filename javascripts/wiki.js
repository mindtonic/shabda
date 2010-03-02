wiki = {
  getSelectedVersions: function(selected) {
    var boxes = new Array()
    var elements = $('compare').elements
    boxes.selected_index = -1

    for(var i = 0; i < elements.length; i++)
      if(elements[i].name == "id[]" && elements[i].checked) {
        if(elements[i] == selected) boxes.selected_index = boxes.length
        boxes.push(elements[i])
      }

    return boxes
  },

  ensureSelectedVersions: function() {
    var boxes = this.getSelectedVersions()
    if(boxes.length == 2) return true
    alert("You must select two versions to compare.")
    return false
  },

  checked: function(checkbox) {
    if(!checkbox.checked) {
      $('compare_button').disabled = true
      return
    }

    var selections = this.getSelectedVersions()
    if(selections.length > 2)
      for(var i = 0; i < selections.length; i++)
        if(selections[i] != checkbox) selections[i].checked = false

    this.examineCompareButton()
  },

  examineCompareButton: function() {
    var boxes = this.getSelectedVersions()
    $('compare_button').disabled = (boxes.length != 2)
  }
}