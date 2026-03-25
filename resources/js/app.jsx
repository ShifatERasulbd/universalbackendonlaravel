import React, { useEffect, useState } from "react";
import ReactDOM from "react-dom/client";
import axios from "axios";
import Installer from "./components/Installer";
import InstallerDatabase from "./components/InstallerDatabase";
import InstallerConfiguration from "./components/InstallerConfiguration";

const path = window.location.pathname;
const isStepTwo = path === "/installer/theme";
const isStepThree = path === "/installer/database";
const isInstallerPath = path === "/installer" || isStepTwo || isStepThree;

function InstallerGuard() {
    const [checking, setChecking] = useState(true);
    const [installedInfo, setInstalledInfo] = useState(null);

    useEffect(() => {
        axios.get("/api/installer/status")
            .then((response) => {
                if (response.data?.installed) {
                    if (isInstallerPath) {
                        window.location.href = response.data.redirect_to || "/";
                        return;
                    }

                    const query = new URLSearchParams(window.location.search);

                    setInstalledInfo({
                        business_category: response.data?.business_category ?? query.get("business_category") ?? "N/A",
                        theme_id: response.data?.theme_id ?? query.get("theme_id") ?? "N/A",
                    });
                } else {
                    setInstalledInfo(null);
                }

                setChecking(false);
            })
            .catch(() => {
                setChecking(false);
            });
    }, []);

    if (checking) {
        return null;
    }

    if (installedInfo) {
        return (
            <div>
                <div>
                    <h2>Application Installed</h2>
                    <p>business_category: {installedInfo.business_category}</p>
                    <p>theme_id: {installedInfo.theme_id}</p>
                </div>
            </div>
        );
    }

    if (isStepThree) return <InstallerConfiguration />;
    if (isStepTwo) return <InstallerDatabase />;
    return <Installer />;
}

ReactDOM.createRoot(document.getElementById("app")).render(
    <React.StrictMode>
        <InstallerGuard />
    </React.StrictMode>
);