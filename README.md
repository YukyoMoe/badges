# badges

一个包含三维吧唧制作与展示的示例项目，前端基于 Three.js，后端使用 PHP 提供贴图上传与数据持久化接口。项目提供两个场景：

1. **展示场景**：默认加载平地，展示所有保存在服务端的吧唧模型，支持通过滑块定位刚刚制作的吧唧。
2. **制作场景**：加载默认模型 `anime_badge_on_backpack.glb`，允许用户上传、裁剪贴图并实时预览在模型上的效果。

## 本地运行

1. 确保本地安装了 PHP（>=7.4）。
2. 在项目根目录执行：

   ```bash
   php -S localhost:8000
   ```

3. 打开浏览器访问 [http://localhost:8000/index.php](http://localhost:8000/index.php) 即可体验。

> 所有上传的贴图会保存在 `uploads/` 目录，对应的位置信息记录在 `data/badges.json` 中。

## 目录结构

```
├── anime_badge_on_backpack.glb   # 默认加载的模型
├── api/                          # PHP 接口目录
├── assets/
│   └── textures/default_badge.png
├── data/badges.json              # 持久化的吧唧数据
├── index.php                     # 前端页面入口
└── uploads/                      # 贴图上传目录
```

## 部署到 GitHub

如果在本地完成了开发但在远端 GitHub 仓库中看不到更新，请确认：

1. 已使用 `git status` 确认工作区干净，并用 `git commit` 提交了所有改动。
2. 仓库已配置远端地址，可通过 `git remote -v` 查看。
3. 使用 `git push origin <分支名>` 将本地提交推送到远端。

只有当提交成功推送后，GitHub 页面上才会显示新的代码与文件。
