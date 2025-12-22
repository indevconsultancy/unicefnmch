*Before running the syntax, please replace XXX with the correct path where the .dat file is located.

DATA LIST FILE='XXX\Exported.dat' RECORDS=1
/
UNIQUE_ID    1-15   (A)
STARTDATETIME    16-36   (A)
ENDDATETIME    37-57   (A)
DEVICE_ID    58-78   (A)
GPS    79-129   (A)
USERNAME    130-170   (A)
SURVEY_STATUS     171-191   (A)
TERMINATION_REASON    192-242   (A).

VARIABLE LABEL.

VALUE LABELS.

EXECUTE.