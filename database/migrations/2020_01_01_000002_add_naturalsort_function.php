<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddNaturalsortFunction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // https://www.drupal.org/project/natsort
        DB::unprepared("
            drop function if exists naturalsort;
            create function naturalsort(s varchar(255)) returns varchar(255)
                no sql
                deterministic
            begin
                declare orig   varchar(255)  default s;
                declare ret    varchar(255)  default '';
                if s is null then
                    return null;
                elseif not s regexp '[0-9]' then
                    set ret = s;
                else
                    set s = replace(replace(replace(replace(replace(s, '0', '#'), '1', '#'), '2', '#'), '3', '#'), '4', '#');
                    set s = replace(replace(replace(replace(replace(s, '5', '#'), '6', '#'), '7', '#'), '8', '#'), '9', '#');
                    set s = replace(s, '.#', '##');
                    set s = replace(s, '#,#', '###');
                    begin
                        declare numpos int;
                        declare numlen int;
                        declare numstr varchar(255);
                        lp1: loop
                            set numpos = locate('#', s);
                            if numpos = 0 then
                                set ret = concat(ret, s);
                                leave lp1;
                            end if;
                            set ret = concat(ret, substring(s, 1, numpos - 1));
                            set s    = substring(s,    numpos);
                            set orig = substring(orig, numpos);
                            set numlen = char_length(s) - char_length(trim(leading '#' from s));
                            set numstr = cast(replace(substring(orig,1,numlen), ',', '') as decimal(13,3));
                            set numstr = lpad(numstr, 15, '0');
                            set ret = concat(ret, '[', numstr, ']');
                            set s    = substring(s,    numlen+1);
                            set orig = substring(orig, numlen+1);
                        end loop;
                    end;
                end if;
                set ret = replace(replace(replace(replace(replace(replace(replace(ret, ' ', ''), ',', ''), ':', ''), '.', ''), ';', ''), '(', ''), ')', '');
                return ret;
            end;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('drop function if exists naturalsort');
    }
}
