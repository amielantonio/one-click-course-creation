/**
 * Check whether the string is inside the array given
 *
 * @param _needle
 * @param _haystack
 * @returns {boolean}
 */
export const inArray = (_needle, _haystack) => {

  _needle = _needle.trim().toLowerCase();

  var length = _haystack.length;

  for(var i = 0; i < length; i++) {
    var _hay = _haystack[i].trim().toLowerCase();

    if(_hay == _needle) return true;
  }
  return false;
};

/**
 * Check whether the string is inside the array given, and includes() as an extra filter
 *
 * @param _needle
 * @param _haystack
 * @returns {boolean}
 */
export const inArraySubstr = (_needle, _haystack) => {


  _needle = _needle.trim().toLowerCase();

  var length = _haystack.length;

  for(var i = 0; i < length; i++) {
    var _hay = _haystack[i].trim().toLowerCase();

    if(_needle.includes(_hay)) return true;
  }
  return false;

};
