/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./app/views/admin/index.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./app/lib/index.js":
/*!**************************!*\
  !*** ./app/lib/index.js ***!
  \**************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = {\n  data() {\n    return {\n      postsSortable: false\n    };\n  },\n\n  created() {\n    this.setPostsSortable();\n  },\n\n  mounted() {\n    const self = this;\n    UIkit.util.on('.list-post', 'moved', function (item) {\n      self.setPostsSortable();\n      self.priorityCheckPosts(self.postsSortable);\n    });\n  },\n\n  methods: {\n    setPostsSortable() {\n      this.postsSortable = document.getElementsByClassName('list-post')[0].children;\n    },\n\n    priorityCheckPosts(object) {\n      for (const key in object) {\n        if (object.hasOwnProperty(key)) {\n          const id = object[key].id;\n          this.posts[id].priority = parseInt(key);\n          this.savePosts(this.posts[id]);\n        }\n      }\n    },\n\n    savePosts(item, reload = false) {\n      this.$http.post('admin/docs/api/save', {\n        data: item,\n        id: item.id\n      }).then(res => {}).catch(err => {\n        this.$notify(err.bodyText, 'danger');\n      });\n    }\n\n  }\n};\n\n//# sourceURL=webpack:///./app/lib/index.js?");

/***/ }),

/***/ "./app/views/admin/index.js":
/*!**********************************!*\
  !*** ./app/views/admin/index.js ***!
  \**********************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\nvar postIndex = {\n  el: '#app',\n  name: 'DocsIndex',\n\n  data() {\n    return _.merge({\n      modalDraft: false,\n      categories: false,\n      posts: false,\n      config: {\n        filter: this.$session.get('documents.filter', {\n          order: 'priority asc',\n          limit: 50\n        })\n      },\n      pages: 0,\n      count: '',\n      selected: [],\n      categorySortable: false\n    }, window.$data);\n  },\n\n  mixins: [__webpack_require__(/*! ../../lib/index */ \"./app/lib/index.js\")],\n\n  created() {\n    this.setCategorySortable();\n    this.resource = this.$resource('admin/docs/api{/id}');\n  },\n\n  mounted() {\n    const self = this;\n    UIkit.util.on('.docs-category', 'moved', function (item) {\n      self.setCategorySortable();\n      self.priorityCheck(self.categorySortable);\n    });\n    this.$watch('config.page', this.load, {\n      immediate: true\n    });\n  },\n\n  watch: {\n    'config.filter': {\n      handler(filter) {\n        if (this.config.page) {\n          this.config.page = 0;\n        } else {\n          this.load();\n        }\n\n        this.$session.set('documents.filter', filter);\n      },\n\n      deep: true\n    }\n  },\n  computed: {\n    categoryOptions() {\n      const options = _.map(this.categories, (category, id) => ({\n        text: category.title,\n        value: category.id\n      }));\n\n      return [{\n        label: this.$trans('Filter by'),\n        options\n      }];\n    },\n\n    draftCategory() {\n      return {\n        id: null,\n        title: null,\n        slug: null,\n        status: 3,\n        roles: []\n      };\n    },\n\n    orderByCategories: function () {\n      return this.categories;\n    }\n  },\n  methods: {\n    load() {\n      this.resource.query({\n        filter: this.config.filter,\n        page: this.config.page\n      }).then(res => {\n        const {\n          data\n        } = res;\n        this.$set(this, 'posts', data.posts);\n        this.$set(this, 'pages', data.pages);\n        this.$set(this, 'count', data.count);\n        this.$set(this, 'selected', []);\n      }).catch(err => {\n        this.$notify(err.bodyText, 'danger');\n      });\n    },\n\n    setCategorySortable() {\n      this.categorySortable = document.getElementsByClassName('docs-category')[0].children;\n    },\n\n    deleteCategory(id) {\n      this.$http.get('admin/docs/api/bulkcategorydelete', {\n        params: {\n          id: id\n        }\n      }).then(res => {\n        location.reload();\n      }).catch(err => {\n        this.$notify(err.data, 'danger');\n      });\n    },\n\n    saveCategory(item, reload = false) {\n      this.$http.post('admin/docs/api/savecategory', {\n        category: item,\n        id: item.id\n      }).then(res => {\n        if (!item.id) {\n          location.reload();\n        }\n\n        if (reload) {\n          location.reload();\n        }\n      }).catch(err => {\n        this.$notify(err.bodyText, 'danger');\n      });\n    },\n\n    priorityCheck(object) {\n      for (const key in object) {\n        if (object.hasOwnProperty(key)) {\n          const id = object[key].id;\n          this.categories[id].priority = parseInt(key);\n          this.saveCategory(this.categories[id]);\n        }\n      }\n    },\n\n    openModal(data) {\n      this.modalDraft = data;\n      this.$refs.modal.open();\n      UIkit.util.on(this.$refs.modal.modal.$el, 'hide', this.onClose);\n    },\n\n    close() {\n      this.modalDraft = this.draftCategory;\n      this.scrollToEnd();\n      this.$refs.modal.close();\n    },\n\n    scrollToEnd() {\n      let container = this.$el.querySelector(\".pk-pre\");\n      if (container && container.scrollHeight) container.scrollTop = container.scrollHeight;\n    },\n\n    onClose() {\n      this.modalDraft = this.draftCategory;\n    },\n\n    status(status) {\n      const posts = this.getSelected();\n      posts.forEach(post => {\n        post.status = status;\n      });\n      this.resource.save({\n        id: 'bulk'\n      }, {\n        posts\n      }).then(function () {\n        this.load();\n        this.$notify('Posts saved.');\n      });\n    },\n\n    remove() {\n      this.resource.delete({\n        id: 'bulk'\n      }, {\n        ids: this.selected\n      }).then(function () {\n        this.load();\n        this.$notify('Posts deleted.');\n      });\n    },\n\n    getSelected() {\n      return this.posts.filter(function (post) {\n        return this.selected.indexOf(post.id) !== -1;\n      }, this);\n    }\n\n  }\n};\n/* harmony default export */ __webpack_exports__[\"default\"] = (postIndex);\nVue.ready(postIndex);\n\n//# sourceURL=webpack:///./app/views/admin/index.js?");

/***/ })

/******/ });