export const form_validation = () => {

  initialize();

};

const initialize = () => {

  const courseContent = document.getElementById('course-content');
  const btnSubmit = document.getElementById('btn-classroom');

  if( courseContent !== null ){
    if (courseContent.value === "" || courseContent.value !== undefined) {
      btnSubmit.setAttribute('disabled', 'disabled');
    }

    courseContent.onchange = () => {
      if(courseContent.value === "" ) {
        btnSubmit.disabled = true;
      } else {
        btnSubmit.disabled = false;
      }
    }
  }
};
