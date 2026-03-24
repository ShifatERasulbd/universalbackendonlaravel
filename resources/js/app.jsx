import React from "react";
import ReactDOM from "react-dom/client";
import Installer from "./components/Installer";
import InstallerDatabase from "./components/InstallerDatabase";

const path = window.location.pathname;
const isStepTwo = path === "/installer/theme";

ReactDOM.createRoot(document.getElementById("app")).render(
    <React.StrictMode>
        {isStepTwo ? <InstallerDatabase /> : <Installer />}
    </React.StrictMode>
);