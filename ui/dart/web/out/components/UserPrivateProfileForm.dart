// Auto-generated from UserPrivateProfileForm.html.
// DO NOT EDIT.

library SolasMatchDart;

import 'dart:html' as autogenerated;
import 'dart:svg' as autogenerated_svg;
import 'package:web_ui/web_ui.dart' as autogenerated;
import 'package:web_ui/observe/observable.dart' as __observe;
import "package:web_ui/web_ui.dart";
import "dart:async";
import "dart:json";
import "dart:html";
import '../../DataAccessObjects/LanguageDao.dart';
import '../../DataAccessObjects/CountryDao.dart';
import '../../DataAccessObjects/UserDao.dart';
import '../../lib/models/Badge.dart';
import '../../lib/models/User.dart';
import '../../lib/models/UserPersonalInformation.dart';
import '../../lib/models/Locale.dart';
import '../../lib/models/Language.dart';
import '../../lib/models/Country.dart';
import '../../lib/Settings.dart';



class UserPrivateProfileForm extends WebComponent with Observable 
{
  /** Autogenerated from the template. */

  autogenerated.ScopedCssMapper _css;

  /** This field is deprecated, use getShadowRoot instead. */
  get _root => getShadowRoot("x-user-private-profile-form");
  static final __html1 = new autogenerated.Element.html('<table>\n          <tbody><tr valign="top" align="center">\n            <td width="50%">\n              <label for="displayName">\n                <strong>\n                  Public Display Name: <span style="color: red">*</span>\n                </strong>\n              </label>\n              <template></template>\n              <input type="text" style="width: 80%">\n\n              <template></template>\n              \n              <div id="languageList"></div>\n \n              <label for="biography"><strong>Biography:</strong></label>\n              <textarea cols="40" rows="7" style="width: 80%">\n\n              </textarea>\n\n            </td>\n            <td width="50%">\n              <label for="firstName"><strong>First Name:</strong></label>\n              <input type="text" style="width: 80%">\n\n              <label for="lastName"><strong>Last Name:</strong></label>\n              <input type="text" style="width: 80%">\n \n              <label for="mobileNumber"><strong>Mobile Number:</strong></label>\n              <input type="text" style="width: 80%">\n\n              <label for="businessNumber"><strong>Business Number:</strong></label>\n              <input type="text" style="width: 80%">\n\n              <label for="sip"><strong>Session Initiation Protocol (SIP):</strong></label>\n              <input type="text" style="width: 80%">\n\n              <label for="jobTitle"><strong>Job Title:</strong></label>\n              <input type="text" style="width: 80%">\n\n              <label for="address"><strong>Address:</strong></label>\n              <textarea cols="40" rows="5" style="width: 80%">\n\n              </textarea>\n\n              <label for="city"><strong>City:</strong></label>\n              <input type="text" style="width: 80%">\n\n              <label for="country"><strong>Country:</strong></label>\n              <input type="text" style="width: 80%">\n            </td>\n          </tr>\n          <tr>\n            <td colspan="2">\n              <hr>\n            </td>\n          </tr>\n          <tr>\n            <td colspan="2">\n              <table>\n                <tbody><tr>\n                  <td colspan="3" align="center" style="font-weight: bold">\n                    Task Type Preferences:\n                  </td>\n                </tr>\n                <tr align="center">\n                  <td>\n                    Translating\n                  </td>\n                  <td>\n                    Proofreading\n                  </td>\n                  <td>\n                    Interpreting\n                  </td>\n                </tr>\n                <tr align="center">\n                  <td>\n                    <input type="checkbox">\n                  </td>\n                  <td>\n                    <input type="checkbox">\n                  </td>\n                  <td>\n                    <input type="checkbox">\n                  </td>\n                </tr>\n              </tbody></table>\n            </td>\n          </tr>\n          <tr>\n            <td colspan="2" style="padding-bottom: 20px">\n              <hr>\n              <template></template>\n            </td>\n          </tr>\n          <tr>\n            <td colspan="2" align="center">\n              <button type="submit" class="btn btn-primary">\n                <i class="icon-refresh icon-white"></i> Update Profile Details\n              </button>\n              <button type="submit" class="btn btn-inverse">\n                <i class="icon-fire icon-white"></i> Delete User Account\n              </button>\n            </td>\n          </tr>\n        </tbody></table>'), __html2 = new autogenerated.Element.html('<span class="alert alert-error"></span>'), __html3 = new autogenerated.Element.html('<p><i>Loading...</i></p>'), __html4 = new autogenerated.Element.html('<p class="alert alert-error">There was an error in the data provided, please check for missing/incorrect data</p>'), __shadowTemplate = new autogenerated.DocumentFragment.html('''
        <template></template>
      ''');
  autogenerated.Element __e23;
  autogenerated.Template __t;

  void created_autogenerated() {
    var __root = createShadowRoot("x-user-private-profile-form");
    setScopedCss("x-user-private-profile-form", new autogenerated.ScopedCssMapper({"x-user-private-profile-form":"[is=\"x-user-private-profile-form\"]"}));
    _css = getScopedCss("x-user-private-profile-form");
    __t = new autogenerated.Template(__root);
    __root.nodes.add(__shadowTemplate.clone(true));
    __e23 = __root.nodes[1];
    __t.conditional(__e23, () => user != null && userInfo != null, (__t) {
      var __e10, __e11, __e12, __e13, __e14, __e15, __e16, __e17, __e18, __e19, __e20, __e21, __e22, __e3, __e4, __e5, __e6, __e7, __e8, __e9;
      __e22 = __html1.clone(true);
      __e3 = __e22.nodes[1].nodes[0].nodes[1].nodes[3];
      __t.conditional(__e3, () => alert != '', (__t) {
        var __e2;
        __e2 = __html2.clone(true);
        var __binding1 = __t.contentBind(() => alert, false);
        __e2.nodes.add(__binding1);
      __t.addAll([new autogenerated.Text('\n                '),
          __e2,
          new autogenerated.Text('\n              ')]);
      });

      __e4 = __e22.nodes[1].nodes[0].nodes[1].nodes[5];
      __t.listen(__e4.onInput, ($event) { user.display_name = __e4.value; });
      __t.oneWayBind(() => user.display_name, (e) { if (__e4.value != e) __e4.value = e; }, false, false);
      __e5 = __e22.nodes[1].nodes[0].nodes[1].nodes[7];
      __t.conditional(__e5, () => !isLoaded, (__t) {
      __t.addAll([new autogenerated.Text('\n                '),
          __html3.clone(true),
          new autogenerated.Text(' \n              ')]);
      });

      __e6 = __e22.nodes[1].nodes[0].nodes[1].nodes[13];
      __t.listen(__e6.onInput, ($event) { user.biography = __e6.value; });
      __t.oneWayBind(() => user.biography, (e) { if (__e6.value != e) __e6.value = e; }, false, false);
      __e7 = __e22.nodes[1].nodes[0].nodes[3].nodes[3];
      __t.listen(__e7.onInput, ($event) { userInfo.firstName = __e7.value; });
      __t.oneWayBind(() => userInfo.firstName, (e) { if (__e7.value != e) __e7.value = e; }, false, false);
      __e8 = __e22.nodes[1].nodes[0].nodes[3].nodes[7];
      __t.listen(__e8.onInput, ($event) { userInfo.lastName = __e8.value; });
      __t.oneWayBind(() => userInfo.lastName, (e) { if (__e8.value != e) __e8.value = e; }, false, false);
      __e9 = __e22.nodes[1].nodes[0].nodes[3].nodes[11];
      __t.listen(__e9.onInput, ($event) { userInfo.mobileNumber = __e9.value; });
      __t.oneWayBind(() => userInfo.mobileNumber, (e) { if (__e9.value != e) __e9.value = e; }, false, false);
      __e10 = __e22.nodes[1].nodes[0].nodes[3].nodes[15];
      __t.listen(__e10.onInput, ($event) { userInfo.businessNumber = __e10.value; });
      __t.oneWayBind(() => userInfo.businessNumber, (e) { if (__e10.value != e) __e10.value = e; }, false, false);
      __e11 = __e22.nodes[1].nodes[0].nodes[3].nodes[19];
      __t.listen(__e11.onInput, ($event) { userInfo.sip = __e11.value; });
      __t.oneWayBind(() => userInfo.sip, (e) { if (__e11.value != e) __e11.value = e; }, false, false);
      __e12 = __e22.nodes[1].nodes[0].nodes[3].nodes[23];
      __t.listen(__e12.onInput, ($event) { userInfo.jobTitle = __e12.value; });
      __t.oneWayBind(() => userInfo.jobTitle, (e) { if (__e12.value != e) __e12.value = e; }, false, false);
      __e13 = __e22.nodes[1].nodes[0].nodes[3].nodes[27];
      __t.listen(__e13.onInput, ($event) { userInfo.address = __e13.value; });
      __t.oneWayBind(() => userInfo.address, (e) { if (__e13.value != e) __e13.value = e; }, false, false);
      __e14 = __e22.nodes[1].nodes[0].nodes[3].nodes[31];
      __t.listen(__e14.onInput, ($event) { userInfo.city = __e14.value; });
      __t.oneWayBind(() => userInfo.city, (e) { if (__e14.value != e) __e14.value = e; }, false, false);
      __e15 = __e22.nodes[1].nodes[0].nodes[3].nodes[35];
      __t.listen(__e15.onInput, ($event) { userInfo.country = __e15.value; });
      __t.oneWayBind(() => userInfo.country, (e) { if (__e15.value != e) __e15.value = e; }, false, false);
      __e16 = __e22.nodes[1].nodes[4].nodes[1].nodes[1].nodes[1].nodes[4].nodes[1].nodes[1];
      __t.listen(__e16.onChange, ($event) { translator = __e16.checked; });
      __t.oneWayBind(() => translator, (e) { if (__e16.checked != e) __e16.checked = e; }, false, false);
      __e17 = __e22.nodes[1].nodes[4].nodes[1].nodes[1].nodes[1].nodes[4].nodes[3].nodes[1];
      __t.listen(__e17.onChange, ($event) { proofreader = __e17.checked; });
      __t.oneWayBind(() => proofreader, (e) { if (__e17.checked != e) __e17.checked = e; }, false, false);
      __e18 = __e22.nodes[1].nodes[4].nodes[1].nodes[1].nodes[1].nodes[4].nodes[5].nodes[1];
      __t.listen(__e18.onChange, ($event) { interpreter = __e18.checked; });
      __t.oneWayBind(() => interpreter, (e) { if (__e18.checked != e) __e18.checked = e; }, false, false);
      __e19 = __e22.nodes[1].nodes[6].nodes[1].nodes[3];
      __t.conditional(__e19, () => alert!= '', (__t) {
      __t.addAll([new autogenerated.Text('\n                '),
          __html4.clone(true),
          new autogenerated.Text('\n              ')]);
      });

      __e20 = __e22.nodes[1].nodes[8].nodes[1].nodes[1];
      __t.listen(__e20.onClick, ($event) { submitForm(); });
      __e21 = __e22.nodes[1].nodes[8].nodes[1].nodes[3];
      __t.listen(__e21.onClick, ($event) { deleteUser(); });
    __t.addAll([new autogenerated.Text('\n        '),
        __e22,
        new autogenerated.Text('\n        ')]);
    });

    __t.create();
  }

  void inserted_autogenerated() {
    __t.insert();
  }

  void removed_autogenerated() {
    __t.remove();
    __t = __e23 = null;
  }

  /** Original code from the component. */

  // xtag attribute
  int userId;
  
  // bound variables
  bool __$translator;
  bool get translator {
    if (__observe.observeReads) {
      __observe.notifyRead(this, __observe.ChangeRecord.FIELD, 'translator');
    }
    return __$translator;
  }
  set translator(bool value) {
    if (__observe.hasObservers(this)) {
      __observe.notifyChange(this, __observe.ChangeRecord.FIELD, 'translator',
          __$translator, value);
    }
    __$translator = value;
  }
  bool __$proofreader;
  bool get proofreader {
    if (__observe.observeReads) {
      __observe.notifyRead(this, __observe.ChangeRecord.FIELD, 'proofreader');
    }
    return __$proofreader;
  }
  set proofreader(bool value) {
    if (__observe.hasObservers(this)) {
      __observe.notifyChange(this, __observe.ChangeRecord.FIELD, 'proofreader',
          __$proofreader, value);
    }
    __$proofreader = value;
  }
  bool __$interpreter;
  bool get interpreter {
    if (__observe.observeReads) {
      __observe.notifyRead(this, __observe.ChangeRecord.FIELD, 'interpreter');
    }
    return __$interpreter;
  }
  set interpreter(bool value) {
    if (__observe.hasObservers(this)) {
      __observe.notifyChange(this, __observe.ChangeRecord.FIELD, 'interpreter',
          __$interpreter, value);
    }
    __$interpreter = value;
  }
  
  // observables
  bool __$isLoaded = false;
  bool get isLoaded {
    if (__observe.observeReads) {
      __observe.notifyRead(this, __observe.ChangeRecord.FIELD, 'isLoaded');
    }
    return __$isLoaded;
  }
  set isLoaded(bool value) {
    if (__observe.hasObservers(this)) {
      __observe.notifyChange(this, __observe.ChangeRecord.FIELD, 'isLoaded',
          __$isLoaded, value);
    }
    __$isLoaded = value;
  }
  User __$user;
  User get user {
    if (__observe.observeReads) {
      __observe.notifyRead(this, __observe.ChangeRecord.FIELD, 'user');
    }
    return __$user;
  }
  set user(User value) {
    if (__observe.hasObservers(this)) {
      __observe.notifyChange(this, __observe.ChangeRecord.FIELD, 'user',
          __$user, value);
    }
    __$user = value;
  }
  UserPersonalInformation __$userInfo;
  UserPersonalInformation get userInfo {
    if (__observe.observeReads) {
      __observe.notifyRead(this, __observe.ChangeRecord.FIELD, 'userInfo');
    }
    return __$userInfo;
  }
  set userInfo(UserPersonalInformation value) {
    if (__observe.hasObservers(this)) {
      __observe.notifyChange(this, __observe.ChangeRecord.FIELD, 'userInfo',
          __$userInfo, value);
    }
    __$userInfo = value;
  }
  int __$secondaryLanguageCount;
  int get secondaryLanguageCount {
    if (__observe.observeReads) {
      __observe.notifyRead(this, __observe.ChangeRecord.FIELD, 'secondaryLanguageCount');
    }
    return __$secondaryLanguageCount;
  }
  set secondaryLanguageCount(int value) {
    if (__observe.hasObservers(this)) {
      __observe.notifyChange(this, __observe.ChangeRecord.FIELD, 'secondaryLanguageCount',
          __$secondaryLanguageCount, value);
    }
    __$secondaryLanguageCount = value;
  }
  List<int> __$secondaryLanguageArray;
  List<int> get secondaryLanguageArray {
    if (__observe.observeReads) {
      __observe.notifyRead(this, __observe.ChangeRecord.FIELD, 'secondaryLanguageArray');
    }
    return __$secondaryLanguageArray;
  }
  set secondaryLanguageArray(List<int> value) {
    if (__observe.hasObservers(this)) {
      __observe.notifyChange(this, __observe.ChangeRecord.FIELD, 'secondaryLanguageArray',
          __$secondaryLanguageArray, value);
    }
    __$secondaryLanguageArray = value;
  }
  List<Locale> __$userSecondaryLanguages;
  List<Locale> get userSecondaryLanguages {
    if (__observe.observeReads) {
      __observe.notifyRead(this, __observe.ChangeRecord.FIELD, 'userSecondaryLanguages');
    }
    return __$userSecondaryLanguages;
  }
  set userSecondaryLanguages(List<Locale> value) {
    if (__observe.hasObservers(this)) {
      __observe.notifyChange(this, __observe.ChangeRecord.FIELD, 'userSecondaryLanguages',
          __$userSecondaryLanguages, value);
    }
    __$userSecondaryLanguages = value;
  }
  List<Language> __$languages;
  List<Language> get languages {
    if (__observe.observeReads) {
      __observe.notifyRead(this, __observe.ChangeRecord.FIELD, 'languages');
    }
    return __$languages;
  }
  set languages(List<Language> value) {
    if (__observe.hasObservers(this)) {
      __observe.notifyChange(this, __observe.ChangeRecord.FIELD, 'languages',
          __$languages, value);
    }
    __$languages = value;
  }
  List<Country> __$countries;
  List<Country> get countries {
    if (__observe.observeReads) {
      __observe.notifyRead(this, __observe.ChangeRecord.FIELD, 'countries');
    }
    return __$countries;
  }
  set countries(List<Country> value) {
    if (__observe.hasObservers(this)) {
      __observe.notifyChange(this, __observe.ChangeRecord.FIELD, 'countries',
          __$countries, value);
    }
    __$countries = value;
  }
  String __$alert;
  String get alert {
    if (__observe.observeReads) {
      __observe.notifyRead(this, __observe.ChangeRecord.FIELD, 'alert');
    }
    return __$alert;
  }
  set alert(String value) {
    if (__observe.hasObservers(this)) {
      __observe.notifyChange(this, __observe.ChangeRecord.FIELD, 'alert',
          __$alert, value);
    }
    __$alert = value;
  }
  
  // misc
  List<String> randomWords;
  List<Badge> badges;
  SelectElement langSelect;
  SelectElement countrySelect;
  
  UserPrivateProfileForm()
  {
    userSecondaryLanguages = toObservable(new List<Locale>());
    languages = toObservable(new List<Language>());
    countries = toObservable(new List<Country>());
    secondaryLanguageArray = toObservable(new List<int>());
    badges = new List<Badge>();
    secondaryLanguageCount = 0;
    translator = false;
    proofreader = false;
    interpreter = false;
    alert = "";
  }
  
  void inserted()
  {
    Settings settings = new Settings();
    settings.loadConf().then((e) {
      List<Future<bool>> dataLoaded = new List<Future<bool>>();
      UserDao.getUserPersonalInfo(userId).then((UserPersonalInformation info) {
        userInfo = info;
      });
      
      UserDao.getUserBadges(userId).then((List<Badge> userBadges) {
        badges = userBadges;
        badges.forEach((Badge badge) {
          if (badge.id == 6) {
            translator = true;
          } else if(badge.id == 7) {
            proofreader = true;
          } else if (badge.id == 8) {
            interpreter = true;
          }
        });
      });
      
      dataLoaded.add(UserDao.getUser(userId).then((User u) {
        user = u;
        return true;
      }));
      
      dataLoaded.add(UserDao.getSecondaryLanguages(userId).then((List<Locale> locales) {
        userSecondaryLanguages.addAll(locales);
        return true;
      }));
      
      dataLoaded.add(LanguageDao.getAllLanguages().then((List<Language> langs) {
        Language lang = new Language();
        lang.name = "Any";
        lang.code = "";
        languages.add(lang);
        languages.addAll(langs);
        return true;
      }));
      
      dataLoaded.add(CountryDao.getAllCountries().then((List<Country> regions) {
        Country any = new Country();
        any.name = "Any";
        any.code = "";
        countries.add(any);
        countries.addAll(regions);
        return true;
      }));
      
      Future.wait(dataLoaded).then((List<bool> successList) => setDefaults(successList)); 
    });
  }
  
  void setDefaults(List<bool> successList)
  {
    successList.forEach((bool success) {
      if (!success) {
        print("Some data failed to load!");
      }
    });
    
    int secLangLength = userSecondaryLanguages.length > 0 ? userSecondaryLanguages.length : 1;
    
    int nativeLanguageIndex = 0;
    int nativeCountryIndex = 0;
    List<int> secondaryLanguageIndex = new List<int>(secLangLength);
    List<int> secondaryCountryIndex = new List<int>(secLangLength);
    
    langSelect = new SelectElement();
    langSelect.style.width = "82%";
    for (int i = 0; i < languages.length; i++) {
      var option = new OptionElement()
          ..value = languages[i].code
          ..text = languages[i].name;
      langSelect.children.add(option);
      
      if (languages[i].code == user.nativeLocale.languageCode) {
        nativeLanguageIndex = i;
      }
      
      if (userSecondaryLanguages.length > 0) {
        for (int j = 0; j < userSecondaryLanguages.length; j++) {
          if (languages[i].code == userSecondaryLanguages[j].languageCode) {
            secondaryLanguageIndex[j] = i;
          }
        }
      }
    }
    
    if (userSecondaryLanguages.length == 0) {
      secondaryLanguageIndex[0] = 0;
    }
    
    countrySelect = new SelectElement();
    countrySelect.style.width = "82%";
    for (int i = 0; i < countries.length; i++) {
      var option = new OptionElement()
          ..value = countries[i].code
          ..text = countries[i].name;
      countrySelect.children.add(option);
      
      if (countries[i].code == user.nativeLocale.countryCode) {
        nativeCountryIndex = i;
      }
      
      if (userSecondaryLanguages.length > 0) {
        for (int j = 0; j < userSecondaryLanguages.length; j++) {
          if (countries[i].code == userSecondaryLanguages[j].countryCode) {
            secondaryCountryIndex[j] = i;
          }
        }
      }
    }
    
    if (userSecondaryLanguages.length == 0) {
      secondaryCountryIndex[0] = 0;
    }
    
    var nativeLanguageDiv = new DivElement()
        ..id = "nativeLanguageDiv";
    var label = new LabelElement()
        ..innerHtml = "<strong>Native Language:</strong>";
    var nativeLanguageSelect = langSelect.clone(true);
    nativeLanguageSelect.id = "nativeLanguageSelect";
    nativeLanguageSelect.selectedIndex = nativeLanguageIndex;
    var nativeCountrySelect = countrySelect.clone(true);
    nativeCountrySelect.id = "nativeCountrySelect";
    nativeCountrySelect.selectedIndex = nativeCountryIndex;
    nativeLanguageDiv.children.add(label);
    nativeLanguageDiv.children.add(nativeLanguageSelect);
    nativeLanguageDiv.children.add(nativeCountrySelect);
    
    var secondaryLanguageDiv = new DivElement()
        ..id = "secondaryLanguageDiv";
    label = new LabelElement()
        ..innerHtml = "<strong>Secondary Language(s):</strong>";
    secondaryLanguageDiv.children.add(label);
    
    ButtonElement button = new ButtonElement()
        ..id = "addLanguageButton"
        ..innerHtml = "<i class='icon-upload icon-white'></i> Add Secondary Language "
        ..classes.add("btn")
        ..classes.add("btn-success")
        ..onClick.listen((event) => addSecondaryLanguage());
    if (userSecondaryLanguages.length > 4) {
      button.disabled = true;
    }
    secondaryLanguageDiv.children.add(button);
    
    button = new ButtonElement()
        ..id = "removeLanguageButton"
        ..innerHtml = "<i class='icon-fire icon-white'></i> Remove"
        ..classes.add("btn")
        ..classes.add("btn-inverse")
        ..onClick.listen((event) => removeSecondaryLanguage());
    if (userSecondaryLanguages.length < 2) {
      button.disabled = true;
    }
    secondaryLanguageDiv.children.add(button);
    
    var div = query("#languageList");
    div.children.add(nativeLanguageDiv);
    div.children.add(secondaryLanguageDiv);
    
    if (userSecondaryLanguages.length > 0) {
      for (int i = 0; i < userSecondaryLanguages.length; i++) {
        this.addSecondaryLanguage(secondaryLanguageIndex[i], secondaryCountryIndex[i]);
      }
    } else {
      this.addSecondaryLanguage(0, 0);
    }
    isLoaded = true;
  }
  
  void addSecondaryLanguage([int languageSelected = 0, int countrySelected = 0])
  {
    if (secondaryLanguageCount < 5) {
      DivElement secondaryLanguageDiv = query("#secondaryLanguageDiv");
      DivElement locale = new DivElement()
          ..id = "secondary_locale_$secondaryLanguageCount";
      SelectElement languageBox = langSelect.clone(true);
      languageBox.id = "secondary_language_$secondaryLanguageCount";
      languageBox.selectedIndex = languageSelected;
      locale.children.add(languageBox);
      SelectElement countryBox = countrySelect.clone(true);
      countryBox.id = "secondary_country_$secondaryLanguageCount";
      countryBox.selectedIndex = countrySelected;
      locale.children.add(countryBox);
      HRElement hr = new HRElement();
      hr.style.width = "60%";
      locale.children.add(hr);
      ButtonElement button = query("#addLanguageButton");
      secondaryLanguageDiv.insertBefore(locale, button);
      secondaryLanguageCount++;
      
      if (secondaryLanguageCount > 4) {
        button = query("#addLanguageButton");
        button.disabled = true;
      }
      
      button = query("#removeLanguageButton");
      if (button.disabled) {
        button.disabled = false;
      }
    }
  }
  
  void removeSecondaryLanguage()
  {
    if (secondaryLanguageCount > 1) {
      secondaryLanguageCount--;
      var element = query("#secondary_locale_$secondaryLanguageCount");
      element.remove();
      
      ButtonElement button = query("#addLanguageButton");
      if (button.disabled) {
        button.disabled = false; 
      }
      
      if (secondaryLanguageCount < 2) {
        button = query("#removeLanguageButton");
        button.disabled = true;
      }
    }
  }
  
  void submitForm()
  {
    alert = "";
    if (user.display_name == "") {
      alert = "Your Display Name cannot be blank.";
    } else {
      List<Future<bool>> updated = new List<Future<bool>>();
      SelectElement nativeLanguageSelect = query("#nativeLanguageSelect");
      SelectElement nativeCountrySelect = query("#nativeCountrySelect");
      if (nativeLanguageSelect.selectedIndex > 0 && nativeCountrySelect.selectedIndex > 0) {
        user.nativeLocale.countryCode = countries[nativeCountrySelect.selectedIndex].code;
        user.nativeLocale.languageCode = languages[nativeLanguageSelect.selectedIndex].code;
      }
      updated.add(UserDao.saveUserDetails(user));
      updated.add(UserDao.saveUserInfo(userInfo));
      
      List<Locale> currentSecondaryLocales = new List<Locale>();
      for (int i = 0; i < secondaryLanguageCount; i++) {
        SelectElement secondaryLanguageSelect = query("#secondary_language_$i");
        SelectElement secondaryCountrySelect = query("#secondary_country_$i");
        if (secondaryLanguageSelect.selectedIndex > 0 && secondaryCountrySelect.selectedIndex > 0) {
          Locale found = userSecondaryLanguages.firstWhere((Locale l) {
            return (l.languageCode == languages[secondaryLanguageSelect.selectedIndex].code
                && l.countryCode == countries[secondaryCountrySelect.selectedIndex].code);
          }, orElse: () {
            Locale locale = new Locale();
            locale.countryCode = countries[secondaryCountrySelect.selectedIndex].code;
            locale.languageCode = languages[secondaryLanguageSelect.selectedIndex].code;
            currentSecondaryLocales.add(locale);
            updated.add(UserDao.addSecondaryLanguage(userId, locale));
          });
          if (found != null) {
            currentSecondaryLocales.add(found);
          }
        }
      }
      
      userSecondaryLanguages.forEach((Locale locale) {
        currentSecondaryLocales.firstWhere((Locale l) {
          return (l.languageCode == locale.languageCode && l.countryCode == locale.countryCode);
        }, orElse: () {
          updated.add(UserDao.removeSecondaryLanguage(userId, locale.languageCode, locale.countryCode));
        });
      });
      
      if (badges != null && badges.length > 0) {
        bool currentlyTranslator = false;
        bool currentlyProofreader = false;
        bool currentlyInterpreter = false;
        badges.forEach((Badge badge) {
          if (badge.id == 6) {
            currentlyTranslator = true;
          } else if (badge.id == 7) {
            currentlyProofreader = true;
          } else if (badge.id == 8) {
            currentlyInterpreter = true;
          }
        });
        if (currentlyTranslator && !translator) {
          updated.add(UserDao.removeUserBadge(userId, 6));
        } else if (!currentlyTranslator && translator) {
          updated.add(UserDao.addUserBadge(userId, 6));
        }
        if (currentlyProofreader && !proofreader) {
          updated.add(UserDao.removeUserBadge(userId, 7));
        } else if (!currentlyProofreader && proofreader) {
          updated.add(UserDao.addUserBadge(userId, 7));
        }
        if (currentlyInterpreter && !interpreter) {
          updated.add(UserDao.removeUserBadge(userId, 8));
        } else if (!currentlyInterpreter && interpreter) {
          updated.add(UserDao.addUserBadge(userId, 8));
        }
      }
      
      Future.wait(updated).then((List<bool> updatesSuccessful) {
        Settings settings = new Settings();
        window.location.assign(settings.conf.urls.SiteLocation + "$userId/profile");
      });
    }
  }
  
  void deleteUser()
  {
    print("Deleting User");
    if (window.confirm("Are you sure you want to permanently delete your account?")) {
      print("Confirmation received");
      UserDao.deleteUser(userId).then((bool success) {
        Settings settings = new Settings();
        window.location.assign(settings.conf.urls.SiteLocation);
      });
    }
  }
}
//# sourceMappingURL=UserPrivateProfileForm.dart.map