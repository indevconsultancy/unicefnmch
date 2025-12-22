import delimited using "data.csv",delimiter(comma) varnames(1) case(preserve) asdouble encoding(UTF-8) clear

label variable uniqueid   "Unique Id"
label variable startDateTime   "Start DateTime"
label variable endDateTime   "End DateTime"
label variable device_id   "Device Id"
label variable GPS   "GPS"
label variable UserName   "UserName"
label variable SurveyStatus   "SurveyStatus"
label variable TerminationReason   "TerminationReason"

#delimit ;

#delimit cr
