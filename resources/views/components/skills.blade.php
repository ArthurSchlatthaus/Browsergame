<img alt="" src="images/profile/skills.png">
<img alt="" src="images/profile/buttons_skills.png" style="position: absolute; left: -1px; top: 327px">
<a style="position:absolute;left: 10px;bottom: 10px; z-index: 11; top: 335px">
    <button class="btn btn-sm" style="background-color: transparent; border:none; color: wheat"
            onclick="showWindow('charWindow')">{{ __('custom.ranking_profile') }}
    </button>
</a>
<a style="position:absolute;left: 75px;bottom: 10px; z-index: 11; top: 335px">
    <button class="btn btn-sm" style="background-color: transparent; border:none; color: wheat"
            onclick="showWindow('skillsWindow')">{{ __('custom.skills_short') }}
    </button>
</a>
<div style="position: absolute; left: 0;top: 0;color: lightgray">
    <a style="top:6px;left:90px"> {{ __('custom.skills') }} </a>
    <div id="skill1Container" style="display:none;">
        <img id="skill_1_1" style="position:absolute; left: 15px; top: 67px;opacity: 0.5;filter: alpha(opacity=50);"
             onclick="setSkill(1)"
             src="images/skills/0_1.png">
        <a id="skill_1_1_label" style="font-size:6pt;position:absolute; left: 33px; top: 88px;"></a>

        <img id="skill_1_2" style="position:absolute; left: 52px; top: 67px;opacity: 0.5;filter: alpha(opacity=50);"
             src="images/skills/0_1.png">
        <a id="skill_1_2_label" style="font-size:6pt;position:absolute; left: 70px; top: 88px;"></a>

        <img id="skill_1_3" style="position:absolute; left: 89px; top: 67px;opacity: 0.5;filter: alpha(opacity=50);"
             src="images/skills/0_1.png">
        <a id="skill_1_3_label" style="font-size:6pt;position:absolute; left: 107px; top: 88px;"></a>
    </div>
    <div id="skill2Container" style="display: none">
        <img id="skill_2_1" style="position:absolute; left: 15px; top: 103px;opacity: 0.5;filter: alpha(opacity=50);"
             onclick="setSkill(2)"
             src="images/skills/0_1.png">
        <a id="skill_2_1_label" style="font-size:6pt;position:absolute; left: 33px; top: 124px;"></a>

        <img id="skill_2_2" style="position:absolute; left: 52px; top: 103px;opacity: 0.5;filter: alpha(opacity=50);"
             src="images/skills/0_1.png">
        <a id="skill_2_2_label" style="font-size:6pt;position:absolute; left: 70px; top: 124px;"></a>

        <img id="skill_2_3" style="position:absolute; left: 89px; top: 103px;opacity: 0.5;filter: alpha(opacity=50);"
             src="images/skills/0_1.png">
        <a id="skill_2_3_label" style="font-size:6pt;position:absolute; left: 107px; top: 124px;"></a>

    </div>

    <div id="classContainer" style="display: none;position:absolute;top: 248px; left: 13px; width: 80px">
        <div>
            <button id="className1" class="btn btn-dark"
                    onclick="setClass(1)"></button>
            <button id="className2" class="btn btn-dark"
                    onclick="setClass(2)"></button>
        </div>
    </div>
    <div id="resetClassContainer" style="display: none;position:absolute;top: 248px; left: 13px; width: 80px">
        <button class="btn btn-dark" onclick="resetClass()">{{ __('custom.reset_class') }}</button>
    </div>

    <p id="freeSkillPoints"
       style="width: 250px; left: 18px;position: absolute; top: 35px;"></p>

</div>
